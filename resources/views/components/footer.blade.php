<footer class="bg-surface-container-low w-full border-t border-outline-variant/40 mt-auto">
    <div class="max-w-7xl mx-auto px-8 py-20 flex flex-col gap-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="flex flex-col gap-6 col-span-1 md:col-span-1">
                <span class="font-headline text-2xl text-primary font-bold">Mataram Stay</span>
                <p class="font-body text-on-surface-variant text-sm leading-relaxed">Menghubungkan Anda dengan pilihan kos terbaik dan pemilik properti terpercaya di Lombok.</p>
            </div>
            <div class="flex flex-col gap-6">
                <h4 class="font-label font-bold uppercase tracking-widest text-[10px] text-on-surface">Kos Dekat Kampus</h4>
                <ul class="flex flex-col gap-4 font-body text-sm text-on-surface-variant">
                    <li><a class="hover:text-primary transition-colors" href="{{ route('search', ['kampus' => 'UNRAM']) }}">UNRAM</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('search', ['kampus' => 'UIN_MATARAM']) }}">UIN Mataram</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('search', ['kampus' => 'POLTEKKES_KEMENKES_MATARAM']) }}">Poltekkes Kemenkes Mataram</a></li>
                </ul>
            </div>
            <div class="flex flex-col gap-6">
                <h4 class="font-label font-bold uppercase tracking-widest text-[10px] text-on-surface">Perusahaan</h4>
                <ul class="flex flex-col gap-4 font-body text-sm text-on-surface-variant">
                    <li><a class="hover:text-primary transition-colors" href="{{ route('tentang') }}">Tentang Mataram Stay</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('blog') }}">Blog</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Karir</a></li>
                </ul>
            </div>
            <div class="flex flex-col gap-6">
                <h4 class="font-label font-bold uppercase tracking-widest text-[10px] text-on-surface">Mitra & Bantuan</h4>
                <ul class="flex flex-col gap-4 font-body text-sm text-on-surface-variant">
                    <li><a class="hover:text-primary transition-colors" href="{{ route('bantuan') }}">Pusat Bantuan</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('syarat-ketentuan') }}">Syarat & Ketentuan</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('kebijakan-privasi') }}">Kebijakan Privasi</a></li>
                </ul>
            </div>
        </div>

        <!-- Payment Trust Bar -->
        <div class="pt-8 border-t border-outline-variant/30">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex flex-wrap items-center gap-3 md:gap-4">
                    <span class="font-body text-xs font-semibold uppercase tracking-wider text-on-surface-variant/70">Metode Pembayaran Aman:</span>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="font-headline font-black text-sm tracking-widest bg-outline-variant/10 text-on-surface/90 px-3 py-1 rounded-lg border border-outline-variant/30">QRIS</span>
                        <span class="font-headline font-bold text-sm bg-outline-variant/10 text-[#00AED6] px-3 py-1 rounded-lg border border-outline-variant/30">GoPay</span>
                        <span class="font-headline font-bold text-xs bg-outline-variant/10 text-on-surface/90 px-3 py-1.5 rounded-lg border border-outline-variant/30 uppercase">Transfer Bank</span>
                        <span class="text-xs text-on-surface-variant/60 font-body">didukung oleh</span>
                        <span class="font-headline font-bold text-sm tracking-tight text-[#142954] bg-outline-variant/10 px-3 py-1 rounded-lg border border-outline-variant/30">Midtrans</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center pt-8 border-t border-outline-variant/30 font-body text-on-surface-variant text-sm gap-6">
            <div>© 2026 Mataram Stay. Crafted for comfort.</div>
            <div class="flex items-center gap-6">
                <a href="#" class="text-on-surface-variant hover:text-primary transition-colors" aria-label="Instagram">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.008 3.81.055.97.045 1.496.207 1.848.344a4.396 4.396 0 011.57 1.021 4.396 4.396 0 011.02 1.57c.138.353.3.879.345 1.848.047 1.026.055 1.379.055 3.81s-.008 2.784-.055 3.81c-.045.97-.207 1.496-.344 1.848a4.396 4.396 0 01-1.021 1.57 4.396 4.396 0 01-1.57 1.02c-.353.137-.879.3-1.848.345-1.026.047-1.379.055-3.81.055s-2.784-.008-3.81-.055c-.97-.045-1.496-.207-1.848-.344a4.396 4.396 0 01-1.57-1.021 4.396 4.396 0 01-1.02-1.57c-.138-.353-.3-.879-.345-1.848C2.008 14.784 2 14.43 2 12s.008-2.784.055-3.81c.045-.97.207-1.496.344-1.848a4.396 4.396 0 011.021-1.57 4.396 4.396 0 011.57-1.02c.353-.138.879-.3 1.848-.345C9.216 2.008 9.57 2 12 2zm0 2.235c-2.408 0-2.693.008-3.642.052-.876.04-1.352.187-1.669.31a2.164 2.164 0 00-.782.51 2.164 2.164 0 00-.51.782c-.122.317-.269.793-.31 1.669-.043.95-.052 1.234-.052 3.642s.009 2.693.052 3.642c.04.876.187 1.352.31 1.669.122.317.269.782.51.782.31.31.6.4.782.51.317.122.793.269 1.669.31.95.043 1.234.052 3.642.052s2.693-.009 3.642-.052c.876-.04 1.352-.187 1.669-.31.317-.122.782-.269.782-.51.31-.31.4-.6.51-.782.122-.317.269-.793.31-1.669.043-.95.052-1.234.052-3.642s-.009-2.693-.052-3.642c-.04-.876-.187-1.352-.31-1.669a2.164 2.164 0 00-.51-.782 2.164 2.164 0 00-.782-.51c-.317-.122-.793-.269-1.669-.31-.95-.043-1.234-.052-3.642-.052zM12 6.865a5.135 5.135 0 100 10.27 5.135 5.135 0 000-10.27zm0 2.235a2.9 2.9 0 110 5.8 2.9 2.9 0 010-5.8zm5.908-.946a1.2 1.2 0 11-2.4 0 1.2 1.2 0 012.4 0z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="#" class="text-on-surface-variant hover:text-primary transition-colors" aria-label="Twitter">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                </a>
                <a href="#" class="text-on-surface-variant hover:text-primary transition-colors" aria-label="TikTok">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12.525.01c1.306-.022 2.527-.01 3.527-.01.08 1.488.948 2.766 2.247 3.442.233.119.482.213.743.282v3.396a8.88 8.88 0 01-3.003-.526 8.944 8.944 0 01-2.222-1.396V14.5c0 4.142-3.358 7.5-7.5 7.5s-7.5-3.358-7.5-7.5 3.358-7.5 7.5-7.5c.348 0 .686.024 1.018.069V10.2a4.429 4.429 0 00-1.018-.118 4.418 4.418 0 00-4.418 4.418 4.418 4.418 0 004.418 4.418 4.418 4.418 0 004.418-4.418V.01z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>