<x-layout>
    <main class="flex-grow max-w-5xl w-full mx-auto px-4 md:px-8 py-16 animate-in fade-in duration-300">
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="material-symbols-outlined text-primary bg-primary/10 p-4 rounded-2xl text-4xl mb-4">explore</span>
            <h1 class="text-4xl md:text-5xl font-headline font-bold text-on-surface tracking-tight">Wisata Lombok</h1>
            <p class="text-secondary font-body mt-4 text-sm md:text-base leading-relaxed">
                Jelajahi keindahan destinasi wisata terbaik di sekitar Mataram dan Pulau Lombok yang dekat dengan hunian Anda.
            </p>
        </div>

        <!-- Destination Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <!-- Card 1: Pantai Senggigi -->
            <div class="bg-white rounded-2xl border border-outline-variant/30 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                <div class="h-48 bg-outline-variant/20 flex flex-col items-center justify-center text-secondary p-6">
                    <span class="material-symbols-outlined text-primary/80 text-5xl mb-2">beach_access</span>
                    <span class="font-headline text-xs font-bold uppercase tracking-wider text-primary">Pantai & Laut</span>
                </div>
                <div class="p-6">
                    <h3 class="font-headline font-bold text-lg text-on-surface mb-2">Pantai Senggigi</h3>
                    <p class="font-body text-sm text-secondary leading-relaxed">Pantai legendaris dengan garis pantai yang panjang dan pemandangan matahari terbenam yang memukau.</p>
                </div>
            </div>

            <!-- Card 2: Desa Wisata Sade -->
            <div class="bg-white rounded-2xl border border-outline-variant/30 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                <div class="h-48 bg-outline-variant/20 flex flex-col items-center justify-center text-secondary p-6">
                    <span class="material-symbols-outlined text-primary/80 text-5xl mb-2">home</span>
                    <span class="font-headline text-xs font-bold uppercase tracking-wider text-primary">Budaya & Sejarah</span>
                </div>
                <div class="p-6">
                    <h3 class="font-headline font-bold text-lg text-on-surface mb-2">Desa Adat Sade</h3>
                    <p class="font-body text-sm text-secondary leading-relaxed">Mengenal kebudayaan suku Sasak yang autentik dengan rumah adat khas dan tenun tradisional.</p>
                </div>
            </div>

            <!-- Card 3: Gili Trawangan -->
            <div class="bg-white rounded-2xl border border-outline-variant/30 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                <div class="h-48 bg-outline-variant/20 flex flex-col items-center justify-center text-secondary p-6">
                    <span class="material-symbols-outlined text-primary/80 text-5xl mb-2">directions_boat</span>
                    <span class="font-headline text-xs font-bold uppercase tracking-wider text-primary">Kepulauan</span>
                </div>
                <div class="p-6">
                    <h3 class="font-headline font-bold text-lg text-on-surface mb-2">Gili Trawangan</h3>
                    <p class="font-body text-sm text-secondary leading-relaxed">Pulau kecil dengan keindahan bawah laut luar biasa tanpa polusi kendaraan bermotor.</p>
                </div>
            </div>
        </div>
    </main>
</x-layout>
