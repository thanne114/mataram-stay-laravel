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
        defaultViewport: { width: 1440, height: 900 }
    });
    const page = await browser.newPage();

    // 1. Seeker - WhatsApp OTP Modal Activity
    console.log('Navigating to Seeker Bypass & Profile...');
    await page.goto('http://127.0.0.1:8000/auth/bypass/seeker', { waitUntil: 'networkidle2' });
    await page.goto('http://127.0.0.1:8000/profile', { waitUntil: 'networkidle2' });
    console.log('Opening WhatsApp OTP Modal...');
    await page.evaluate(() => {
        // Switch to verification view section
        if (typeof switchView === 'function') {
            const btn = document.querySelector('a[onclick*="view-verifikasi"]');
            switchView('view-verifikasi', btn);
        }
        // Open phone OTP modal
        if (typeof openOtpModal === 'function') {
            openOtpModal();
        }
    });
    await sleep(2000);
    await page.screenshot({ path: path.join(screenshotsDir, 'activity_whatsapp_otp_modal.png'), fullPage: false });
    console.log('Captured WhatsApp OTP Modal activity.');

    // 2. Seeker - Booking Create Form Activity
    console.log('Navigating to Booking Create Form...');
    await page.goto('http://127.0.0.1:8000/booking/create?room_type_id=1', { waitUntil: 'networkidle2' });
    await sleep(2000);
    await page.screenshot({ path: path.join(screenshotsDir, 'activity_seeker_booking_create.png'), fullPage: true });
    console.log('Captured Seeker Booking Form activity.');

    // 3. Seeker - Booking Detail Pending Payment Activity
    console.log('Navigating to Booking Pending Detail...');
    await page.goto('http://127.0.0.1:8000/booking/1', { waitUntil: 'networkidle2' });
    await sleep(2000);
    await page.screenshot({ path: path.join(screenshotsDir, 'activity_seeker_booking_detail_unpaid.png'), fullPage: true });
    console.log('Captured Seeker Booking Pending activity.');

    // 4. Owner - Reviewing and Approving Transactions Activity
    console.log('Navigating to Owner Bypass & Transactions...');
    await page.goto('http://127.0.0.1:8000/auth/bypass/owner', { waitUntil: 'networkidle2' });
    await page.goto('http://127.0.0.1:8000/owner/transactions', { waitUntil: 'networkidle2' });
    await sleep(2000);
    await page.screenshot({ path: path.join(screenshotsDir, 'activity_owner_transactions.png'), fullPage: true });
    console.log('Captured Owner Transactions list activity.');

    // 5. Admin - Verifying Seeker Identitas Activity
    console.log('Navigating to Admin Bypass & Verifikasi Pencari...');
    await page.goto('http://127.0.0.1:8000/auth/bypass/admin', { waitUntil: 'networkidle2' });
    await page.goto('http://127.0.0.1:8000/dashboard-admin', { waitUntil: 'networkidle2' });
    await page.evaluate(() => {
        // Click Verifikasi Pencari tab button using the correct function name
        if (typeof switchTab === 'function') {
            switchTab('seekers');
        }
    });
    await sleep(3000);
    await page.screenshot({ path: path.join(screenshotsDir, 'activity_admin_verify_seeker.png'), fullPage: true });
    console.log('Captured Admin Verifikasi Pencari activity.');

    // 6. Admin - Approving New Property Activity
    console.log('Navigating to Admin Persetujuan Kos...');
    await page.evaluate(() => {
        // Click Persetujuan Kos tab button using the correct function name
        if (typeof switchTab === 'function') {
            switchTab('properties');
        }
    });
    await sleep(3000);
    await page.screenshot({ path: path.join(screenshotsDir, 'activity_admin_approve_property.png'), fullPage: true });
    console.log('Captured Admin Persetujuan Kos activity.');

    await browser.close();
    console.log('All detailed activity screenshots captured successfully!');
}

run();
