import puppeteer from 'puppeteer';
import fs from 'fs';
import path from 'path';

const screenshotsDir = 'c:/xampp/htdocs/mataram-stay/screenshots';
if (!fs.existsSync(screenshotsDir)){
    fs.mkdirSync(screenshotsDir, { recursive: true });
}

const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

async function run() {
    console.log('Launching browser...');
    let executablePath = undefined;
    if (fs.existsSync('C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe')) {
        executablePath = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
    }
    const browser = await puppeteer.launch({
        headless: true,
        executablePath,
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
        defaultViewport: { width: 1440, height: 1100 }
    });
    const page = await browser.newPage();

    console.log('Navigating to Seeker Bypass...');
    await page.goto('http://127.0.0.1:8000/auth/bypass/seeker', { waitUntil: 'networkidle2' });
    
    // 1. Capture Keamanan & Password for Seeker
    console.log('Opening Seeker Profile Settings (Keamanan)...');
    await page.goto('http://127.0.0.1:8000/profile?tab=view-settings', { waitUntil: 'networkidle2' });
    console.log('Expanding Keamanan & Password...');
    await page.evaluate(() => {
        const buttons = Array.from(document.querySelectorAll('button'));
        const btn = buttons.find(b => b.textContent.includes('Keamanan & Password'));
        if (btn) btn.click();
    });
    await sleep(2000);
    await page.screenshot({ path: path.join(screenshotsDir, 'seeker_settings_keamanan.png'), fullPage: true });
    console.log('Captured seeker_settings_keamanan.png');

    // 2. Capture Sesi Aktif for Seeker
    console.log('Refreshing page to reset state...');
    await page.goto('http://127.0.0.1:8000/profile?tab=view-settings', { waitUntil: 'networkidle2' });
    console.log('Expanding Sesi Aktif...');
    await page.evaluate(() => {
        const buttons = Array.from(document.querySelectorAll('button'));
        const btn = buttons.find(b => b.textContent.includes('Sesi Aktif'));
        if (btn) btn.click();
    });
    await sleep(2000);
    await page.screenshot({ path: path.join(screenshotsDir, 'seeker_settings_sesi.png'), fullPage: true });
    console.log('Captured seeker_settings_sesi.png');

    await browser.close();
    console.log('All Seeker settings screenshots captured successfully!');
}

run();
