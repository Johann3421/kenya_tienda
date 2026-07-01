const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch({
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    const page = await browser.newPage();
    
    // Capture console logs
    page.on('console', msg => {
        console.log(`BROWSER_CONSOLE: ${msg.type().toUpperCase()}: ${msg.text()}`);
    });
    page.on('pageerror', err => {
        console.log(`BROWSER_ERROR: ${err.toString()}`);
    });

    await page.goto('http://localhost:8000/consultar/garantia', { waitUntil: 'networkidle2' });
    
    // Type and click
    await page.type('input[placeholder="Ingrese su número de serie"]', 'DM258010009331');
    await page.click('button');
    
    // Wait a bit
    await page.waitForTimeout(3000);
    
    await browser.close();
})();
