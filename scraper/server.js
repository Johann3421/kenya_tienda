const express = require('express');
const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');

puppeteer.use(StealthPlugin());

const app = express();
const port = 3000;

app.get('/scrape', async (req, res) => {
    const slug = req.query.slug;
    if (!slug) {
        return res.status(400).json({ error: 'Falta parámetro slug' });
    }

    const url = `https://nanoreview.net/en/cpu/${slug}`;
    console.log(`[Scraper] Fetching: ${url}`);

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
        
        // Bloquear recursos innecesarios para ir más rápido
        await page.setRequestInterception(true);
        page.on('request', (req) => {
            if (['image', 'stylesheet', 'font', 'media'].includes(req.resourceType())) {
                req.abort();
            } else {
                req.continue();
            }
        });

        const response = await page.goto(url, { waitUntil: 'networkidle2', timeout: 30000 });
        
        const title = await page.title();
        if (title.includes('Just a moment')) {
            console.log(`[Scraper] Cloudflare detectado para ${slug}. Esperando a que se resuelva...`);
            await page.waitForFunction('!document.title.includes("Just a moment")', { timeout: 20000 }).catch(() => {});
        }
        
        const nextData = await page.evaluate(() => {
            const script = document.getElementById('__NEXT_DATA__');
            return script ? script.textContent : null;
        });
        
        if (!nextData) {
            console.log(`[Scraper] No se encontró __NEXT_DATA__ para ${slug}`);
            return res.status(404).json({ error: 'Procesador no encontrado en Nanoreview (404)' });
        }
        
        try {
            const jsonData = JSON.parse(nextData);
            
            // La información del CPU suele estar en props.pageProps.cpu
            const cpuData = jsonData?.props?.pageProps?.cpu || jsonData?.props?.pageProps || jsonData;
            
            res.json(cpuData);
        } catch (parseError) {
            console.log(`[Scraper] Error parseando JSON de __NEXT_DATA__`);
            res.status(500).json({ error: 'No se pudo parsear el JSON de __NEXT_DATA__' });
        }
        
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
