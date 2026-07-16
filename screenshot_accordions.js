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

    console.log('Navigating to Owner Bypass...');
    await page.goto('http://127.0.0.1:8000/auth/bypass/owner', { waitUntil: 'networkidle2' });
    
    // 1. Capture Informasi Rekening Pencairan
    console.log('Opening Owner Profile Settings...');
    await page.goto('http://127.0.0.1:8000/profile', { waitUntil: 'networkidle2' });
    console.log('Expanding Informasi Rekening Pencairan...');
    await page.evaluate(() => {
        const buttons = Array.from(document.querySelectorAll('button'));
        const btn = buttons.find(b => b.textContent.includes('Informasi Rekening Pencairan'));
        if (btn) btn.click();
    });
    await sleep(2000);
    await page.screenshot({ path: path.join(screenshotsDir, 'owner_settings_rekening.png'), fullPage: true });
    console.log('Captured owner_settings_rekening.png');

    // 2. Capture Keamanan & Password
    console.log('Refreshing page to reset state...');
    await page.goto('http://127.0.0.1:8000/profile', { waitUntil: 'networkidle2' });
    console.log('Expanding Keamanan & Password...');
    await page.evaluate(() => {
        const buttons = Array.from(document.querySelectorAll('button'));
        const btn = buttons.find(b => b.textContent.includes('Keamanan & Password'));
        if (btn) btn.click();
    });
    await sleep(2000);
    await page.screenshot({ path: path.join(screenshotsDir, 'owner_settings_keamanan.png'), fullPage: true });
    console.log('Captured owner_settings_keamanan.png');

    // 3. Capture Sesi Aktif
    console.log('Refreshing page to reset state...');
    await page.goto('http://127.0.0.1:8000/profile', { waitUntil: 'networkidle2' });
    console.log('Expanding Sesi Aktif...');
    await page.evaluate(() => {
        const buttons = Array.from(document.querySelectorAll('button'));
        const btn = buttons.find(b => b.textContent.includes('Sesi Aktif'));
        if (btn) btn.click();
    });
    await sleep(2000);
    await page.screenshot({ path: path.join(screenshotsDir, 'owner_settings_sesi.png'), fullPage: true });
    console.log('Captured owner_settings_sesi.png');

    await browser.close();
    console.log('All accordion screenshots captured successfully!');
}

run();
