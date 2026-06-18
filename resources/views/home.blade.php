<x-layout>
    <main class="flex-grow flex flex-col items-center">
        
        <section class="w-full relative min-h-[70vh] flex flex-col justify-center items-center px-4 md:px-8 py-20 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCuEH_caEyLh7FPtwWwEdkwMSgypMgLClq82i4r7GOA0oFiIPiNj7F9KbpB_30V2AjsjKqjl50UMl5a30hQRO74eMgtz5NDb6LFV4DJiNwxUR9XZ5yQYqKpgabLbYuEWHxwtDoVOGiXgyZVh97ZQI3ovMU6hv7C6flItc2AOHKcjxOJfblx8StERcbiQ-7jk1-ru8RK0U1s4u8AopAcvkrIp9V9bVIgfGnF12VhIIScd0AoZsHLZuVYdFAJijIaDoZpYlSVoCl81QE');">
            <div class="absolute inset-0 bg-on-background/40"></div>
            <div class="relative z-10 max-w-4xl w-full text-center mb-12 px-4">
                
                {{-- JIKA SUDAH LOGIN: Tampilkan Sapaan Nama --}}
                @auth
                    <h1 class="font-headline text-5xl md:text-7xl font-bold text-surface-bright mb-6 leading-tight drop-shadow-md">
                        Halo, {{ auth()->user()->name ?? 'Juragan' }}! Siap menemukan hunian baru hari ini?
                    </h1>
                @endauth

                {{-- JIKA BELUM LOGIN: Tampilkan Teks Promosi Default --}}
                @guest
                    <h1 class="font-headline text-5xl md:text-8xl font-medium text-surface-bright mb-8 leading-[1.1] drop-shadow-2xl">
                        Sewa Kos di Mataram Kini Bebas Ribet.
                    </h1>
                @endguest

                <p class="font-body text-xl md:text-2xl text-surface-variant max-w-3xl mx-auto drop-shadow-lg font-light tracking-wide">
                    Temukan hunian terverifikasi. Pilih kamar, bayar aman, dan langsung pindah hari ini juga.
                </p>
            </div>
            
            <div class="relative z-10 w-full max-w-5xl bg-surface/85 backdrop-blur-xl p-2 md:p-3 rounded-2xl shadow-2xl border border-white/20">
                <form action="{{ route('search') }}" method="GET" class="flex flex-col md:flex-row gap-2">
                    <div class="flex-1 flex flex-col md:flex-row">
                        <div class="flex-1 px-4 py-3 md:py-4 border-b md:border-b-0 md:border-r border-outline-variant/30 flex flex-col gap-1">
                            <span class="text-[10px] uppercase tracking-widest font-bold text-secondary px-1">Lokasi</span>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-xl">location_on</span>
                                <select name="lokasi" class="w-full bg-transparent border-none focus:ring-0 text-on-surface font-body text-base outline-none cursor-pointer p-0 appearance-none">
                                    <option value="">Semua Kecamatan</option>
                                    <option value="Selaparang">Selaparang</option>
                                    <option value="Mataram">Mataram</option>
                                    <option value="Cakranegara">Cakranegara</option>
                                    <option value="Ampenan">Ampenan</option>
                                    <option value="Sekarbela">Sekarbela</option>
                                    <option value="Sandubaya">Sandubaya</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex-1 px-4 py-3 md:py-4 border-b md:border-b-0 md:border-r border-outline-variant/30 flex flex-col gap-1">
                            <span class="text-[10px] uppercase tracking-widest font-bold text-secondary px-1">Tipe Kos</span>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-xl">group</span>
                                <select name="tipe_kos" class="w-full bg-transparent border-none focus:ring-0 text-on-surface font-body text-base outline-none cursor-pointer p-0 appearance-none">
                                    <option value="">Semua Tipe</option>
                                    <option value="Putra">Putra</option>
                                    <option value="Putri">Putri</option>
                                    <option value="Campur">Campur</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex-1 px-4 py-3 md:py-4 flex flex-col gap-1">
                            <span class="text-[10px] uppercase tracking-widest font-bold text-secondary px-1">Rentang Harga</span>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-xl">payments</span>
                                <div class="flex items-center gap-1.5 w-full">
                                    <input type="number" name="harga_minimal" placeholder="Min" class="w-1/2 bg-transparent border-none focus:ring-0 text-on-surface font-body text-base outline-none p-0 placeholder-secondary/50" min="0">
                                    <span class="text-secondary/60 text-sm font-bold">-</span>
                                    <input type="number" name="harga_maksimal" placeholder="Max" class="w-1/2 bg-transparent border-none focus:ring-0 text-on-surface font-body text-base outline-none p-0 placeholder-secondary/50" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="bg-primary text-on-primary px-10 py-4 md:py-0 rounded-xl font-label font-bold hover:bg-primary-container transition-all shadow-lg flex items-center justify-center gap-3 group active:scale-95" type="submit">
                        <span class="material-symbols-outlined group-hover:scale-110 transition-transform">search</span>
                        Cari Sekarang
                    </button>
                </form>
            </div>
        </section>

        @auth
            @if(isset($bookingAktif))
                <section class="w-full max-w-7xl mx-auto px-4 md:px-8 py-12 -mt-8 relative z-20">
                    <div class="bg-surface-container-low rounded-2xl border border-outline-variant/60 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-md">
                        <div class="flex flex-col md:flex-row items-center gap-6">
                            <div class="w-24 h-24 rounded-lg overflow-hidden flex-shrink-0 border border-outline-variant/40">
                                <img alt="Foto Kos" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDtVYj90mEJhdRDbDXRQW6kpNiHqYp2fY5z-zkRTcx9xiNWyKyPXWjKizKihQ69sNcTlGSzXaZqExbdYd-Adger9Ygro9Vo__5S7JG6o77nIzRBr2KYnUk42LcYPS7mspAESNnyIqlOyT3Hy8mSw6ntdnippQuf8eQK1fjYuV8vbeXZxRNTz95phUtJiz8KM2RKwXooQcwYyWrm7DrxvvvUL32PRYoKEyLm3I_dNOBGigFNuHahhUOan9rzQYP4F5zDeBQqTJAH-2E">
                            </div>
                            <div class="flex flex-col gap-1 text-center md:text-left">
                                <span class="font-label text-xs font-bold text-primary uppercase tracking-widest">Pemesanan Aktif</span>
                                <h2 class="font-headline text-2xl font-bold text-on-surface">{{ $bookingAktif->roomType->property->name }}</h2>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-tertiary-fixed text-on-tertiary-fixed-variant border border-tertiary/20">
                                        {{ $bookingAktif->status }}
                                    </span>
                                    <span class="text-secondary text-sm font-label">• Check-in: {{ $bookingAktif->check_in_date->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('booking.show', $bookingAktif) }}" class="w-full md:w-auto bg-primary text-on-primary px-8 py-3.5 rounded-lg font-label font-bold hover:bg-primary-fixed-dim transition-all shadow-sm flex items-center justify-center gap-2 group text-center">
                            Lihat Detail Status
                        </a>
                    </div>
                </section>
            @endif
        @endauth

        <section class="w-full max-w-7xl mx-auto px-6 md:px-8 py-24 flex flex-col gap-12">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="flex flex-col gap-3">
                    <h2 class="font-headline text-4xl md:text-5xl font-medium text-on-surface">Pilihan Terpopuler</h2>
                    <p class="font-body text-secondary text-lg max-w-xl">Temukan hunian dengan rating tertinggi yang paling direkomendasikan.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($popularProperties as $property)
                    <article class="group bg-white rounded-3xl border border-outline-variant/30 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col">
                        <div class="relative aspect-[4/5] overflow-hidden">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" src="{{ $property->main_image ? asset('storage/' . $property->main_image) : 'https://placehold.co/400x500' }}">
                            <div class="absolute top-4 left-4 bg-on-background text-surface-bright px-3 py-1 rounded-full text-[10px] font-label font-bold tracking-widest uppercase">
                                {{ $property->type }}
                            </div>
                        </div>
                        <div class="p-5 flex flex-col gap-3">
                            <div class="flex justify-between items-start">
                                <h3 class="font-headline text-2xl font-bold text-on-surface tracking-tight leading-tight hover:text-primary transition-colors">
                                    <a href="{{ route('property.show', $property->slug) }}">{{ $property->name }}</a>
                                </h3>
                                <div class="flex items-center gap-1 shrink-0 pt-1">
                                    <span class="material-symbols-outlined text-primary text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="font-label text-sm font-bold text-on-surface">{{ $property->average_rating ?? 'Baru' }}</span>
                                </div>
                            </div>
                            <div class="flex items-center text-secondary gap-1">
                                <span class="material-symbols-outlined text-sm">location_on</span>
                                <span class="font-label text-xs">{{ $property->area }}, Mataram</span>
                            </div>
                            <div class="flex flex-col gap-2 mt-2">
                                <div class="flex items-baseline gap-1">
                                    <span class="font-label font-bold text-xl text-primary">Rp {{ number_format($property->lowest_price, 0, ',', '.') }}</span>
                                    <span class="font-label text-xs text-secondary">/ bln</span>
                                </div>
                                <div class="inline-flex {{ $property->available_rooms > 0 ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100' }} px-3 py-1 rounded-full border self-start">
                                    <span class="font-label text-[10px] font-bold uppercase tracking-tight">
                                        {{ $property->available_rooms }} Kamar Tersedia
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-12 text-secondary">
                        <span class="material-symbols-outlined text-4xl mb-2">hotel</span>
                        <p>Belum ada kos-kosan yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="w-full flex justify-center mt-12">
                <a href="{{ route('search') }}" class="inline-flex items-center gap-3 border-2 border-primary text-primary px-10 py-4 rounded-xl font-label font-bold hover:bg-primary hover:text-on-primary transition-all duration-300 group shadow-sm hover:shadow-lg">
                    Lihat Semua Properti
                </a>
            </div>
        </section>

        <!-- Section: Kos Sekitar Kampus -->
        @php
            $campuses = [
                [
                    'name' => 'UNRAM',
                    'location' => 'Kekalik',
                    'query' => 'UNRAM',
                    'logo' => asset('images/campuses/unram.png'),
                    'initials' => 'UR'
                ],
                [
                    'name' => 'UIN Mataram Kampus 2',
                    'location' => 'Jempong Baru',
                    'query' => 'UIN_MATARAM_2',
                    'logo' => asset('images/campuses/uin.png'),
                    'initials' => 'UIN'
                ],
                [
                    'name' => 'Poltekkes Kemenkes Mataram',
                    'location' => 'Dasan Cermen',
                    'query' => 'POLTEKKES_KEMENKES_MATARAM',
                    'logo' => asset('images/campuses/polnam.png'),
                    'initials' => 'PK'
                ],
                [
                    'name' => 'UT Mataram',
                    'location' => 'Jempong Baru',
                    'query' => 'UT_MATARAM',
                    'logo' => asset('images/campuses/ut.png'),
                    'initials' => 'UT'
                ],
                [
                    'name' => 'UMMAT',
                    'location' => 'Pagesangan',
                    'query' => 'UMMAT',
                    'logo' => asset('images/campuses/ummat.png'),
                    'initials' => 'UM'
                ],
                [
                    'name' => 'UTM',
                    'location' => 'Kekalik',
                    'query' => 'UTM',
                    'logo' => asset('images/campuses/utm.png'),
                    'initials' => 'UTM'
                ],
                [
                    'name' => 'UNBIM',
                    'location' => 'Sekarbela',
                    'query' => 'UNBIM',
                    'logo' => asset('images/campuses/unbim.png'),
                    'initials' => 'UB'
                ],
            ];
        @endphp
        <section class="w-full max-w-7xl mx-auto px-6 md:px-8 pb-24 flex flex-col gap-8">
            <div class="flex flex-col gap-2">
                <h2 class="font-headline text-3xl md:text-4xl font-medium text-on-surface">Kos Sekitar Kampus</h2>
                <p class="font-body text-secondary text-sm">Cari hunian kos strategis yang dekat dengan lokasi kampus Anda.</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($campuses as $campus)
                    <a href="{{ route('search', ['kampus' => $campus['query']]) }}" class="flex items-center gap-4 bg-white border border-outline-variant/30 rounded-xl p-4 hover:shadow-md transition group">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl overflow-hidden bg-primary/10 text-primary flex items-center justify-center group-hover:scale-105 transition-all shadow-sm">
                            <img src="{{ $campus['logo'] }}" alt="{{ $campus['name'] }}" class="w-full h-full object-contain p-1" id="logo-{{ $campus['query'] }}" onerror="this.style.display='none'; document.getElementById('fallback-{{ $campus['query'] }}').style.display='flex';">
                            <div id="fallback-{{ $campus['query'] }}" class="hidden w-full h-full items-center justify-center font-headline font-bold text-xs text-primary uppercase">
                                {{ $campus['initials'] }}
                            </div>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="font-bold text-on-surface truncate font-body text-sm md:text-base leading-tight group-hover:text-primary transition-colors">{{ $campus['name'] }}</span>
                            <span class="text-xs text-secondary truncate font-body">{{ $campus['location'] }}</span>
                        </div>
                    </a>
                @endforeach
                
                <!-- 8th Card: Lihat Semua -->
                <a href="{{ route('kampus.index') }}" class="flex items-center justify-center bg-white border border-outline-variant/30 rounded-xl p-4 hover:shadow-md transition group min-h-[72px]">
                    <span class="font-bold text-primary flex items-center gap-1 font-body text-sm md:text-base">
                        Lihat semua
                        <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </span>
                </a>
            </div>
        </section>

        <section class="w-full max-w-7xl mx-auto px-6 md:px-8 mb-24">
            <div class="bg-primary-container/20 rounded-[2rem] p-8 md:p-16 flex flex-col md:flex-row items-center justify-between gap-12 border border-primary/10">
                <div class="max-w-xl flex flex-col items-start">
                    <h2 class="font-headline text-4xl md:text-5xl font-medium text-on-background mb-6">Punya Properti untuk Disewakan?</h2>
                    <p class="font-body text-on-surface-variant text-lg mb-8 leading-relaxed">Bergabunglah dengan ribuan pemilik kos lainnya di Mataram Stay. Kelola listing Anda dengan mudah dan jangkau penyewa potensial lebih luas.</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ auth()->check() && (auth()->user()->isOwner() || auth()->user()->isAdmin()) ? route('property.create') : route('register') }}" class="bg-primary text-on-primary px-10 py-4 rounded-xl font-label font-bold hover:scale-105 transition-all shadow-xl block text-center">Daftarkan Properti</a>
                        <a href="https://api.whatsapp.com/send/?phone=6281337594955&text=Hi%2C%0ASaya+tertarik+bergabung+dengan+Mataram+Stay%0ANama+Pemilik%3A+{{ auth()->check() ? urlencode(auth()->user()->name) : '' }}%0ANama+Properti%3A%0ATipe+Properti%3A+%28Putra+%2F+Putri+%2F+Campur%29%0AKecamatan%3A+%28Selaparang+%2F+Mataram+%2F+Cakranegara+%2F+Ampenan+%2F+Sekarbela+%2F+Sandubaya%29" target="_blank" class="bg-white text-on-surface border border-outline px-10 py-4 rounded-xl font-label font-bold hover:bg-surface-variant transition-all shadow-md block text-center">Konsultasi Properti</a>
                    </div>
                </div>
                <div class="w-full md:w-1/3 aspect-square rounded-3xl overflow-hidden shadow-2xl">
                    <img alt="Host" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD0X6_DSKoZJ1qSmKP8KPQA626ycW2zUE8VEgaamZGdk_UPSCga6MDgajf9LjOrnqVMx5Mwj5Kf8JwoS-lbSr2zY2CUjwxzvSA9-2-aCIWvdJ1qd4nqucwBCN9piD_B3gXY0gdYa2tyKgAyZDWUuzmgmNG1uLTUmNQAu5cxZ7Vv46XHCL-UAgrVwV3UZNE1f6wAzBPX-8g8imabUoiqcRHZC5q-BAfRZAN2rOFayS78DB46rrhZ2q3dB9PIT461lWPfk-APvsMlnw4">
                </div>
            </div>
        </section>
    </main>
</x-layout>
