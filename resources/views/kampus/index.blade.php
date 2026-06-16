<x-layout>
    <main class="w-full max-w-7xl mx-auto px-4 md:px-8 py-12 flex-grow">
        <!-- Breadcrumb -->
        <nav class="flex text-sm text-secondary mb-6 font-body" aria-label="Breadcrumb">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <span class="mx-2">/</span>
            <span class="text-on-surface font-semibold">Sitemap Kampus</span>
        </nav>

        <div class="flex flex-col md:flex-row gap-8 bg-surface-container-lowest rounded-3xl border border-outline-variant/30 overflow-hidden shadow-sm">
            <!-- Sidebar Kiri -->
            <aside class="w-full md:w-1/4 bg-surface-container-low/40 p-6 md:p-8 border-b md:border-b-0 md:border-r border-outline-variant/30 flex flex-col gap-6">
                <div>
                    <h2 class="font-label text-xs font-bold uppercase tracking-widest text-secondary mb-4">SITEMAPS</h2>
                    <ul class="flex flex-col gap-3 font-body text-sm">
                        <li>
                            <span class="text-secondary cursor-not-allowed">Kos di Area Populer</span>
                        </li>
                        <li>
                            <a href="{{ route('kampus.index') }}" class="font-bold text-primary flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                Kos Dekat Kampus di Mataram
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>

            <!-- Konten Utama Kanan -->
            <section class="w-full md:w-3/4 p-6 md:p-10 bg-white">
                <h1 class="font-headline text-3xl md:text-4xl font-semibold text-on-surface mb-8 tracking-tight">
                    Kos Dekat Kampus di Mataram
                </h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- PTN -->
                    <div class="flex flex-col gap-4">
                        <h2 class="font-label text-base font-bold text-on-surface border-b border-outline-variant/30 pb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings: 'FILL' 1;">account_balance</span>
                            Perguruan Tinggi Negeri (PTN)
                        </h2>
                        <ul class="flex flex-col gap-3 font-body text-sm">
                            @foreach($campuses['ptn'] as $campus)
                                <li>
                                    <a href="{{ route('search', ['kampus' => $campus['query']]) }}" class="hover:text-primary transition-colors flex items-center gap-3 py-1 group">
                                        <div class="flex-shrink-0 w-7 h-7 rounded-lg overflow-hidden bg-primary/10 text-primary flex items-center justify-center shadow-sm">
                                            <img src="{{ $campus['logo'] }}" alt="{{ $campus['name'] }}" class="w-full h-full object-contain p-0.5" id="logo-{{ $campus['query'] }}" onerror="this.style.display='none'; document.getElementById('fallback-{{ $campus['query'] }}').style.display='flex';">
                                            <div id="fallback-{{ $campus['query'] }}" class="hidden w-full h-full items-center justify-center font-headline font-bold text-[9px] text-primary uppercase">
                                                {{ $campus['initials'] }}
                                            </div>
                                        </div>
                                        <span class="text-secondary group-hover:text-primary group-hover:underline text-sm leading-tight transition-colors">{{ $campus['display'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- PTS -->
                    <div class="flex flex-col gap-4">
                        <h2 class="font-label text-base font-bold text-on-surface border-b border-outline-variant/30 pb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings: 'FILL' 1;">apartment</span>
                            Perguruan Tinggi Swasta (PTS)
                        </h2>
                        <ul class="flex flex-col gap-3 font-body text-sm">
                            @foreach($campuses['pts'] as $campus)
                                <li>
                                    <a href="{{ route('search', ['kampus' => $campus['query']]) }}" class="hover:text-primary transition-colors flex items-center gap-3 py-1 group">
                                        <div class="flex-shrink-0 w-7 h-7 rounded-lg overflow-hidden bg-primary/10 text-primary flex items-center justify-center shadow-sm">
                                            <img src="{{ $campus['logo'] }}" alt="{{ $campus['name'] }}" class="w-full h-full object-contain p-0.5" id="logo-{{ $campus['query'] }}" onerror="this.style.display='none'; document.getElementById('fallback-{{ $campus['query'] }}').style.display='flex';">
                                            <div id="fallback-{{ $campus['query'] }}" class="hidden w-full h-full items-center justify-center font-headline font-bold text-[9px] text-primary uppercase">
                                                {{ $campus['initials'] }}
                                            </div>
                                        </div>
                                        <span class="text-secondary group-hover:text-primary group-hover:underline text-sm leading-tight transition-colors">{{ $campus['display'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </main>
</x-layout>
