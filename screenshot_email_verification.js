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
    
    // 1. Capture Email OTP Verification Modal
    console.log('Opening Profile Settings...');
    await page.goto('http://127.0.0.1:8000/profile?tab=view-settings', { waitUntil: 'networkidle2' });
    console.log('Opening Email OTP Verification Modal...');
    await page.evaluate(() => {
        if (typeof openEmailOtpModal === 'function') {
            openEmailOtpModal();
        }
    });
    await sleep(2000);
    await page.screenshot({ path: path.join(screenshotsDir, 'activity_seeker_email_otp_modal.png'), fullPage: false });
    console.log('Captured activity_seeker_email_otp_modal.png');

    // 2. Capture Ubah Email Activity
    console.log('Refreshing page to reset state...');
    await page.goto('http://127.0.0.1:8000/profile?tab=view-settings', { waitUntil: 'networkidle2' });
    console.log('Typing new email...');
    await page.focus('#profile_email_field');
    // Clear the field
    await page.keyboard.down('Control');
    await page.keyboard.press('KeyA');
    await page.keyboard.up('Control');
    await page.keyboard.press('Backspace');
    // Type new email
    await page.keyboard.type('seeker_baru@mataramstay.com');
    await sleep(1000);
    await page.screenshot({ path: path.join(screenshotsDir, 'activity_seeker_change_email.png'), fullPage: false });
    console.log('Captured activity_seeker_change_email.png');

    await browser.close();
    console.log('All email activity screenshots captured successfully!');
}

run();
