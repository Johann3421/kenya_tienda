const puppeteer = require('puppeteer');

(async () => {
    try {
        const browser = await puppeteer.launch({ headless: true });
        const page = await browser.newPage();

        page.on('console', msg => console.log('PAGE LOG:', msg.text()));
        page.on('pageerror', error => console.log('PAGE ERROR:', error.message));

        await page.goto('http://localhost:8000/consultar/garantia', { waitUntil: 'networkidle2' });

        const isVueMounted = await page.evaluate(() => {
            return !!document.querySelector('#garantia').__vue__;
        });

        console.log('Is Vue Mounted:', isVueMounted);

        if (isVueMounted) {
            await page.type('input[placeholder="Ingrese su número de serie"]', 'DM258010009331');
            await page.click('button');
            await page.waitForTimeout(3000);
            
            const resultsHtml = await page.evaluate(() => {
                return document.querySelector('#main-results-container').outerHTML;
            });
            console.log('Results HTML length:', resultsHtml.length);
            console.log('Results HTML snippet:', resultsHtml.substring(0, 300));
        }

        await browser.close();
    } catch (e) {
        console.error(e);
    }
})();
