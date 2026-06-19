<nav class="bg-background w-full top-0 sticky border-b border-outline-variant/60 shadow-sm z-50">
    <div class="hidden md:flex justify-between items-center px-8 py-4 max-w-7xl mx-auto">
        <div class="flex items-center gap-8">
            <a class="flex items-center gap-2" href="/">
                <span class="font-headline text-xl font-bold text-[#c2652a]">Mataram Stay</span>
            </a>
            
            <ul class="flex gap-10 ml-12">
                <li class="relative">
                    <a class="{{ (request()->is('/') || request()->routeIs('dashboard.seeker')) ? 'text-on-surface' : 'text-secondary hover:text-[#c2652a]' }} font-label font-bold pb-2 transition-colors" href="/">Cari Kos</a>
                    @if(request()->is('/') || request()->routeIs('dashboard.seeker'))
                        <div class="absolute -bottom-1 left-0 w-full h-[3px] bg-[#c2652a] rounded-full"></div>
                    @endif
                </li>
                <li class="relative">
                    <a class="{{ request()->is('wisata') ? 'text-on-surface' : 'text-secondary hover:text-[#c2652a]' }} font-label font-medium pb-2 transition-colors" href="#">Wisata</a>
                    @if(request()->is('wisata'))
                        <div class="absolute -bottom-1 left-0 w-full h-[3px] bg-[#c2652a] rounded-full"></div>
                    @endif
                </li>
                <li class="relative">
                    <a class="{{ request()->is('bantuan') ? 'text-on-surface' : 'text-secondary hover:text-[#c2652a]' }} font-label font-medium pb-2 transition-colors" href="{{ route('bantuan') }}">Bantuan</a>
                    @if(request()->is('bantuan'))
                        <div class="absolute -bottom-1 left-0 w-full h-[3px] bg-[#c2652a] rounded-full"></div>
                    @endif
                </li>
            </ul>
        </div>
        
        <div class="flex items-center gap-6">
            @guest
                @if(!request()->routeIs('register') && !request()->routeIs('login'))
                    <a href="/login" class="font-label text-on-surface font-medium hover:text-primary transition-colors text-sm">Log In</a>
                    <a href="/register" class="bg-primary text-on-primary px-6 py-2 rounded-lg font-label font-bold text-sm hover:bg-primary-container transition-all shadow-sm active:scale-95">Sign Up</a>
                @endif
            @endguest
            @auth
                @php
                    $dashboardUrl = match(auth()->user()->role) {
                        'owner' => route('dashboard.owner'),
                        'seeker' => route('profile.edit'),
                        'admin' => route('dashboard.admin'),
                        default => '/',
                    };
                    $roleLabel = match(auth()->user()->role) {
                        'owner' => 'Pemilik Kos',
                        'seeker' => 'Pencari Kos',
                        'admin' => 'Administrator',
                        default => ucfirst(auth()->user()->role),
                    };
                    $portalLabel = match(auth()->user()->role) {
                        'seeker' => 'Ruang Penyewa',
                        'owner' => 'Mitra Properti',
                        'admin' => 'Admin Portal',
                        default => 'Portal',
                    };
                    $transactionUrl = match(auth()->user()->role) {
                        'owner' => route('transactions.owner'),
                        'seeker' => route('transactions.seeker'),
                        default => '#',
                    };
                @endphp

                <!-- Dropdown Container -->
                <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                    <!-- Trigger -->
                    <button @click="open = !open" class="flex items-center gap-3 cursor-pointer group focus:outline-none text-right">
                        <div class="flex flex-col items-end">
                            <span class="font-label text-sm font-bold text-on-surface group-hover:text-primary transition-colors">{{ auth()->user()->name }}</span>
                            <span class="font-label text-[10px] text-secondary uppercase tracking-wider">
                                {{ $roleLabel }}
                            </span>
                        </div>
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-10 h-10 rounded-full border border-outline-variant object-cover group-hover:border-primary transition-colors" alt="Profile">
                        @else
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center border border-outline-variant font-bold text-primary group-hover:border-primary transition-colors">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-3 w-56 rounded-xl bg-white border border-outline-variant/60 shadow-lg py-2 z-50 text-left font-body text-sm"
                         style="display: none;">
                        
                        <a href="{{ $dashboardUrl }}" class="flex items-center gap-2.5 px-4 py-2.5 text-on-surface hover:bg-surface-variant/45 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-lg">dashboard</span>
                            {{ $portalLabel }}
                        </a>

                        @if(auth()->user()->role !== 'admin')
                            <a href="{{ $transactionUrl }}" class="flex items-center gap-2.5 px-4 py-2.5 text-on-surface hover:bg-surface-variant/45 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-lg">receipt_long</span>
                                Transaksi
                            </a>

                            <a href="{{ route('profile.edit', ['tab' => 'view-verifikasi']) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-on-surface hover:bg-surface-variant/45 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-lg">verified_user</span>
                                Verifikasi Akun
                            </a>
                        @endif

                        <hr class="border-t border-outline-variant/40 my-1.5">

                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-left text-tertiary hover:bg-tertiary/5 transition-colors">
                                <span class="material-symbols-outlined text-lg">logout</span>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
    <!-- Load Alpine.js for Dropdown functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</nav>