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
                    $unreadMessagesCount = \App\Models\Message::where('is_read', false)
                        ->where('sender_id', '!=', auth()->id())
                        ->whereHas('conversation', function ($query) {
                            $query->where('seeker_id', auth()->id())
                                ->orWhere('owner_id', auth()->id());
                        })
                        ->count();

                    $hasNotification = (auth()->user()->role === 'seeker' && !auth()->user()->is_verified) || ($unreadMessagesCount > 0);

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
                @endphp
                <div class="flex items-center gap-4">
                    <a href="{{ $dashboardUrl }}" class="flex items-center gap-3 cursor-pointer group">
                        <div class="flex flex-col items-end">
                            <span class="font-label text-sm font-bold text-on-surface group-hover:text-primary transition-colors">{{ auth()->user()->name }}</span>
                            <span class="font-label text-[10px] text-secondary uppercase tracking-wider">
                                {{ $roleLabel }}
                            </span>
                        </div>
                        <div class="relative">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-10 h-10 rounded-full border border-outline-variant object-cover" alt="Profile">
                            @else
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center border border-outline-variant font-bold text-primary">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            @if($hasNotification)
                                <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 border-2 border-white rounded-full"></span>
                            @endif
                        </div>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="ml-2">
                        @csrf
                        <button type="submit" class="text-secondary hover:text-tertiary font-label text-sm font-bold transition-colors">Logout</button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>