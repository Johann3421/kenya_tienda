const express = require('express');
const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');

puppeteer.use(StealthPlugin());

const app = express();
const port = 3000;

/**
 * Lanza un browser compartido para no recrearlo en cada request.
 * Si cae, se vuelve a crear en la siguiente petición.
 */
let browserInstance = null;

async function getBrowser() {
    if (browserInstance) {
        try {
            // Comprobación rápida de que sigue vivo
            await browserInstance.version();
            return browserInstance;
        } catch (_) {
            browserInstance = null;
        }
    }
    browserInstance = await puppeteer.launch({
        executablePath: '/usr/bin/chromium',
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-gpu',
        ],
        headless: true
    });
    return browserInstance;
}

/**
 * Bloquea recursos innecesarios para acelerar la carga de cada página.
 */
async function setupPage(browser) {
    const page = await browser.newPage();
    await page.setRequestInterception(true);
    page.on('request', (req) => {
        if (['image', 'stylesheet', 'font', 'media'].includes(req.resourceType())) {
            req.abort();
        } else {
            req.continue();
        }
    });
    // User-Agent de Chrome real
    await page.setUserAgent(
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36'
    );
    return page;
}

app.get('/scrape', async (req, res) => {
    const q = req.query.q;
    if (!q) {
        return res.status(400).json({ error: 'Falta parámetro q' });
    }

    // ─── PASO 1: Buscar en DuckDuckGo HTML (no usa AJAX, no bloquea bots) ────────
    const ddgQuery = encodeURIComponent(`site:techpowerup.com/cpu-specs/ ${q}`);
    const searchUrl = `https://html.duckduckgo.com/html/?q=${ddgQuery}`;
    console.log(`[Scraper] Buscando en DDG: ${searchUrl}`);

    let page;
    try {
        const browser = await getBrowser();
        page = await setupPage(browser);

        await page.goto(searchUrl, { waitUntil: 'domcontentloaded', timeout: 30000 });

        // Esperar a que carguen los resultados
        await page.waitForSelector('a.result__url, .result__a', { timeout: 15000 }).catch(() => {});

        // Extraer el primer href que apunte a techpowerup.com/cpu-specs/
        const cpuUrl = await page.evaluate(() => {
            const links = Array.from(document.querySelectorAll('a'));
            const match = links.find(a => {
                const href = a.getAttribute('href') || '';
                return href.includes('techpowerup.com/cpu-specs/') && href.includes('.c');
            });
            if (match) return match.getAttribute('href');

            // Fallback: buscar en texto de resultados
            const resultLinks = Array.from(document.querySelectorAll('.result__url, .result__a'));
            const fallback = resultLinks.find(el => el.textContent.includes('techpowerup.com/cpu-specs'));
            return fallback ? fallback.getAttribute('href') : null;
        });

        if (!cpuUrl) {
            console.log(`[Scraper] No se encontró URL en DDG para: ${q}`);
            await page.close();
            return res.status(404).json({ error: `No se encontró '${q}' en TechPowerUp vía DuckDuckGo` });
        }

        // Asegurarse de que la URL sea absoluta
        const finalUrl = cpuUrl.startsWith('http')
            ? cpuUrl
            : `https://www.techpowerup.com${cpuUrl}`;

        console.log(`[Scraper] URL encontrada: ${finalUrl}`);

        // ─── PASO 2: Navegar a la página del CPU en TechPowerUp ──────────────────
        await page.goto(finalUrl, { waitUntil: 'domcontentloaded', timeout: 30000 });

        // Esperar a que aparezcan las specs
        await page.waitForSelector('dt', { timeout: 10000 }).catch(() => {});

        // ─── PASO 3: Extraer especificaciones ────────────────────────────────────
        const specs = await page.evaluate(() => {
            const data = {};

            // Prioridad 1: definition lists <dt>/<dd> (estructura moderna de TPU)
            document.querySelectorAll('dt').forEach(dt => {
                const dd = dt.nextElementSibling;
                if (dd && dd.tagName === 'DD') {
                    const key = dt.innerText.trim();
                    const val = dd.innerText.replace(/\s+/g, ' ').trim();
                    if (key && val) data[key] = val;
                }
            });

            // Prioridad 2: tablas <th>/<td> (estructura alternativa de TPU)
            if (Object.keys(data).length === 0) {
                document.querySelectorAll('tr').forEach(tr => {
                    const th = tr.querySelector('th');
                    const td = tr.querySelector('td');
                    if (th && td) {
                        const key = th.innerText.trim();
                        const val = td.innerText.replace(/\s+/g, ' ').trim();
                        if (key && val) data[key] = val;
                    }
                });
            }

            return data;
        });

        await page.close();

        // Validar que tenemos datos reales (al menos "Cores")
        if (!specs['Cores'] && !specs['Threads']) {
            console.log(`[Scraper] Specs vacías o inválidas para: ${q}`, JSON.stringify(specs).substring(0, 200));
            return res.status(500).json({ error: 'No se pudieron extraer specs válidas', partial: specs });
        }

        console.log(`[Scraper] OK: ${specs['Cores']} cores, ${specs['Base Clock']} base`);
        res.json({ ...specs, _source_url: finalUrl });

    } catch (error) {
        console.error(`[Scraper] Error fatal:`, error.message);
        if (page) await page.close().catch(() => {});
        browserInstance = null; // Forzar recreación en siguiente request
        res.status(500).json({ error: error.message });
    }
});

app.listen(port, () => {
    console.log(`[Scraper] Microservicio listo en puerto ${port}`);
});
