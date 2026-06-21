<x-layout title="Admin Gateway - Mataram Stay">
    <main class="flex-grow flex items-center justify-center min-h-[calc(100vh-8rem)] py-12 px-4">
        <div class="bg-surface rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row w-full max-w-6xl min-h-[600px] border border-outline-variant/30">
            <!-- Left Side: Decorative & Title -->
            <section class="hidden md:flex md:w-1/2 relative flex-col justify-end p-12">
                <div class="absolute inset-0 z-0">
                    <img alt="Office Interior" class="w-full h-full object-cover grayscale-[0.2] brightness-[0.7]" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAWHuZCwMVPrJhD1uHyhI5ql0uBJEB_Qe-IFoM4RgK5aVSRJBZuavenvKxf149QR8pdb35ZSAM1Lxc81hFPKAeYBhSTNA06cdNIX4m-AUXaj9W3ky2UmhUkDI79xXkFej9NDdvvGPF70ciiz1pMxgVMeTBdo1yLN9faKYmNSagkrxdfJJmBJg8LeEQcNFAfe5-43DXNqEcrDvo60hUWSqqZGnE40fubi-qM_G5OwlOvGFiosuJeISAn4lFPh5QX6jb9t4dbn1lQDbw">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/35 to-transparent"></div>
                </div>
                <div class="relative z-10">
                    <span class="text-xs font-bold uppercase tracking-widest text-primary bg-primary/10 border border-primary/20 px-3 py-1 rounded-full whitespace-nowrap self-start">ADMINISTRATOR</span>
                    <h1 class="font-headline text-4xl text-white mt-4 mb-4 leading-tight">Mataram Stay Management.</h1>
                    <p class="text-white/80 text-base font-light tracking-wide">Pusat kendali operasional, verifikasi dokumen mitra, dan manajemen aliran dana transaksi escrow.</p>
                </div>
            </section>

            <!-- Right Side: Credentials Form -->
            <section class="w-full md:w-1/2 bg-surface px-6 py-12 md:p-16 flex flex-col justify-center">
                <div class="max-w-md w-full mx-auto">
                    <div class="mb-8">
                        <h2 class="font-headline text-3xl font-medium text-on-background tracking-tight mb-2">Pintu Masuk Admin</h2>
                        <p class="text-on-surface-variant font-body text-sm">Gunakan email instansi Anda untuk memproses verifikasi dan audit sistem.</p>
                    </div>

                    @if($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">error</span>
                                    <p class="font-bold text-xs">{{ $error }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                        @csrf
                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Email Administrator</label>
                            <input name="email" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="email" placeholder="admin@mataramstay.my.id" value="{{ old('email') }}" required autofocus/>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Kata Sandi</label>
                            <div class="relative group">
                                <input name="password" id="password-field" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm pr-12" placeholder="••••••••" type="password" required/>
                                <button class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary hover:text-primary p-1 focus:outline-none" onclick="togglePassword()" type="button">
                                    <span class="material-symbols-outlined text-xl" id="toggle-icon">visibility</span>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                                <span class="text-xs text-on-surface-variant font-medium">Ingat Sesi Saya</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl text-base font-bold shadow-md hover:bg-primary-container hover:text-on-primary-container transition-all duration-300 active:scale-[0.98]">
                            Masuk Dasbor Admin
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <script>
        function togglePassword() {
            const pwdField = document.getElementById('password-field');
            const icon = document.getElementById('toggle-icon');
            if (pwdField.type === 'password') {
                pwdField.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                pwdField.type = 'password';
                icon.textContent = 'visibility';
            }
        }
    </script>
</x-layout>
