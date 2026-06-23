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
    const browser = await puppeteer.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
        defaultViewport: { width: 1440, height: 900 }
    });
    const page = await browser.newPage();

    const tasks = [
        // 1. Public Views
        { url: 'http://127.0.0.1:8000/', name: 'public_home.png' },
        { url: 'http://127.0.0.1:8000/search', name: 'public_search.png' },
        { url: 'http://127.0.0.1:8000/kos/kos-selaparang-modern', name: 'public_property_detail.png' },
        { url: 'http://127.0.0.1:8000/bantuan', name: 'public_bantuan.png' },
        { url: 'http://127.0.0.1:8000/wisata', name: 'public_wisata.png' },
        { url: 'http://127.0.0.1:8000/syarat-ketentuan', name: 'public_syarat_ketentuan.png' },
        { url: 'http://127.0.0.1:8000/kebijakan-privasi', name: 'public_kebijakan_privasi.png' },
        { url: 'http://127.0.0.1:8000/tentang', name: 'public_tentang.png' },
        { url: 'http://127.0.0.1:8000/blog', name: 'public_blog.png' },
        { url: 'http://127.0.0.1:8000/kampus', name: 'public_kampus.png' },

        // 2. Guest/Auth
        { url: 'http://127.0.0.1:8000/login', name: 'guest_login.png' },
        { url: 'http://127.0.0.1:8000/register', name: 'guest_register.png' },

        // 3. Seeker Views
        { url: 'http://127.0.0.1:8000/auth/bypass/seeker', name: 'seeker_dashboard.png' },
        { url: 'http://127.0.0.1:8000/seeker/transactions', name: 'seeker_transactions.png' },
        { url: 'http://127.0.0.1:8000/booking/1', name: 'seeker_booking_detail_pending.png' },
        { url: 'http://127.0.0.1:8000/booking/3', name: 'seeker_booking_detail_active.png' },
        { url: 'http://127.0.0.1:8000/chat', name: 'seeker_chat.png' },
        { url: 'http://127.0.0.1:8000/profile', name: 'seeker_profile.png' },

        // 4. Owner Views
        { url: 'http://127.0.0.1:8000/auth/bypass/owner', name: 'owner_dashboard.png' },
        { url: 'http://127.0.0.1:8000/owner/transactions', name: 'owner_transactions.png' },
        { url: 'http://127.0.0.1:8000/property/create', name: 'owner_property_create.png' },
        { url: 'http://127.0.0.1:8000/chat', name: 'owner_chat.png' },

        // 5. Admin Views
        { url: 'http://127.0.0.1:8000/auth/bypass/admin', name: 'admin_dashboard.png' }
    ];

    for (const t of tasks) {
        console.log(`Navigating to ${t.url}...`);
        try {
            await page.goto(t.url, { waitUntil: 'networkidle2', timeout: 30000 });
            await sleep(3000); // Allow styles and maps to render
            
            const dest = path.join(screenshotsDir, t.name);
            console.log(`Saving screenshot to ${t.name}...`);
            await page.screenshot({ path: dest, fullPage: true });
        } catch (e) {
            console.error(`Error capturing ${t.name}:`, e.message);
        }
    }

    await browser.close();
    console.log('All screenshots captured successfully!');
}

run();
