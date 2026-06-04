const express = require('express');
const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');

puppeteer.use(StealthPlugin());

const app = express();
const port = 3000;

app.get('/scrape', async (req, res) => {
    const q = req.query.q;
    if (!q) {
        return res.status(400).json({ error: 'Falta parámetro q' });
    }

    const searchUrl = `https://www.techpowerup.com/cpu-specs/?ajaxsrch=${encodeURIComponent(q)}`;
    console.log(`[Scraper] Searching TPU: ${searchUrl}`);

    let browser;
    try {
        browser = await puppeteer.launch({
            executablePath: '/usr/bin/chromium',
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-gpu',
            ],
            headless: true
        });

        const page = await browser.newPage();
        
        // Bloquear recursos innecesarios
        await page.setRequestInterception(true);
        page.on('request', (req) => {
            if (['image', 'stylesheet', 'font', 'media'].includes(req.resourceType())) {
                req.abort();
            } else {
                req.continue();
            }
        });

        // 1. Navegar a ajaxsrch
        await page.goto(searchUrl, { waitUntil: 'networkidle2', timeout: 30000 });
        
        const title = await page.title();
        if (title.includes('Just a moment')) {
            console.log(`[Scraper] Cloudflare detectado en búsqueda. Esperando...`);
            await page.waitForFunction('!document.title.includes("Just a moment")', { timeout: 20000 }).catch(() => {});
        }
        
        // Extraer el primer enlace (href) de los resultados
        const cpuPath = await page.evaluate(() => {
            const link = document.querySelector('a');
            return link ? link.getAttribute('href') : null;
        });

        if (!cpuPath) {
            console.log(`[Scraper] No se encontraron resultados para: ${q}`);
            return res.status(404).json({ error: 'Procesador no encontrado en TechPowerUp (404)' });
        }

        // 2. Navegar a la página del CPU
        const cpuUrl = cpuPath.startsWith('http') ? cpuPath : `https://www.techpowerup.com${cpuPath}`;
        console.log(`[Scraper] Visitando CPU: ${cpuUrl}`);
        
        await page.goto(cpuUrl, { waitUntil: 'networkidle2', timeout: 30000 });
        
        const cpuTitle = await page.title();
        if (cpuTitle.includes('Just a moment')) {
            await page.waitForFunction('!document.title.includes("Just a moment")', { timeout: 20000 }).catch(() => {});
        }

        // 3. Extraer especificaciones
        const specs = await page.evaluate(() => {
            const data = {};
            
            // TPU suele usar definition lists en las páginas modernas
            const dts = document.querySelectorAll('dt');
            dts.forEach(dt => {
                const dd = dt.nextElementSibling;
                if (dd && dd.tagName === 'DD') {
                    // Remover texto extrañp o saltos de línea innecesarios
                    data[dt.innerText.trim()] = dd.innerText.replace(/\s+/g, ' ').trim();
                }
            });

            // Fallback para TPU si usa tablas (<tr> con <th> y <td>)
            const trs = document.querySelectorAll('tr');
            trs.forEach(tr => {
                const th = tr.querySelector('th');
                const td = tr.querySelector('td');
                if (th && td) {
                    data[th.innerText.trim()] = td.innerText.replace(/\s+/g, ' ').trim();
                }
            });

            return data;
        });

        res.json(specs);
        
    } catch (error) {
        console.error(`[Scraper] Error:`, error);
        res.status(500).json({ error: error.message });
    } finally {
        if (browser) {
            await browser.close();
        }
    }
});

app.listen(port, () => {
    console.log(`Scraper microservice listening on port ${port}`);
});
