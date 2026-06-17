<x-layout>
    <main class="flex-grow max-w-5xl w-full mx-auto px-4 md:px-8 py-16 animate-in fade-in duration-300">
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="material-symbols-outlined text-primary bg-primary/10 p-4 rounded-2xl text-4xl mb-4">article</span>
            <h1 class="text-4xl md:text-5xl font-headline font-bold text-on-surface tracking-tight">Blog & Artikel</h1>
            <p class="text-secondary font-body mt-4 text-sm md:text-base leading-relaxed">
                Temukan tips seputar kehidupan anak kos, panduan dekorasi kamar, serta artikel menarik lainnya di blog kami.
            </p>
        </div>

        <!-- Blog Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <!-- Article 1 -->
            <div class="bg-white rounded-2xl border border-outline-variant/30 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                <div class="h-48 bg-outline-variant/20 flex flex-col items-center justify-center p-6 text-center">
                    <span class="material-symbols-outlined text-primary/80 text-5xl mb-2">tips_and_updates</span>
                </div>
                <div class="p-6">
                    <span class="text-xs font-bold text-primary uppercase font-headline">Tips Kos</span>
                    <h3 class="font-headline font-bold text-lg text-on-surface mt-2 mb-3">5 Cara Mendekorasi Kamar Kos Agar Terlihat Luas</h3>
                    <p class="font-body text-sm text-secondary leading-relaxed">Simak trik penataan furnitur dan pemilihan warna cat dinding untuk mengubah kamar kos sempit menjadi lebih nyaman.</p>
                </div>
            </div>

            <!-- Article 2 -->
            <div class="bg-white rounded-2xl border border-outline-variant/30 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                <div class="h-48 bg-outline-variant/20 flex flex-col items-center justify-center p-6 text-center">
                    <span class="material-symbols-outlined text-primary/80 text-5xl mb-2">payments</span>
                </div>
                <div class="p-6">
                    <span class="text-xs font-bold text-primary uppercase font-headline">Keuangan</span>
                    <h3 class="font-headline font-bold text-lg text-on-surface mt-2 mb-3">Tips Mengatur Keuangan Bulanan Mahasiswa Rantau</h3>
                    <p class="font-body text-sm text-secondary leading-relaxed">Panduan praktis menghemat pengeluaran makan, transportasi, dan kebutuhan bulanan agar tidak bokek di akhir bulan.</p>
                </div>
            </div>
        </div>
    </main>
</x-layout>
