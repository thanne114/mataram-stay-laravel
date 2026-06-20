<x-layout title="Masuk - Mataram Stay">
    <main class="flex-grow flex items-center justify-center min-h-[calc(100vh-8rem)] py-12 px-4">
        <div class="bg-surface rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row w-full max-w-6xl min-h-[600px] border border-outline-variant/30">
            <!-- Left Side: Image & Welcome Text -->
            <section class="hidden md:flex md:w-1/2 relative flex-col justify-end p-12">
                <div class="absolute inset-0 z-0">
                    <img alt="Warm Interior" class="w-full h-full object-cover grayscale-[0.1] brightness-[0.85]" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAWHuZCwMVPrJhD1uHyhI5ql0uBJEB_Qe-IFoM4RgK5aVSRJBZuavenvKxf149QR8pdb35ZSAM1Lxc81hFPKAeYBhSTNA06cdNIX4m-AUXaj9W3ky2UmhUkDI79xXkFej9NDdvvGPF70ciiz1pMxgVMeTBdo1yLN9faKYmNSagkrxdfJJmBJg8LeEQcNFAfe5-43DXNqEcrDvo60hUWSqqZGnE40fubi-qM_G5OwlOvGFiosuJeISAn4lFPh5QX6jb9t4dbn1lQDbw">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                </div>
                <div class="relative z-10">
                    <h1 class="font-headline text-4xl text-white mb-4 leading-tight">Pesan Kos di Mataram Kini Lebih Mudah.</h1>
                    <p class="text-white/80 text-base font-light tracking-wide">Jelajahi ratusan pilihan kos terverifikasi dengan harga transparan dan proses booking instan yang 100% aman.</p>
                </div>
            </section>

            <!-- Right Side: Google SSO Auth -->
            <section class="w-full md:w-1/2 bg-surface px-6 py-12 md:p-16 flex flex-col justify-center">
                <div class="max-w-md w-full mx-auto">
                    <div class="mb-8">
                        <h2 class="font-headline text-3xl font-medium text-on-background tracking-tight mb-2">Selamat Datang Kembali</h2>
                        <p class="text-on-surface-variant font-body text-sm">Masuk ke akun Anda untuk melanjutkan pencarian hunian impian.</p>
                    </div>

                    @if(session('error'))
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3">
                            <span class="material-symbols-outlined text-sm">error</span>
                            <p class="font-bold text-xs">{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="space-y-8 pt-6 flex flex-col items-center">
                        <a href="/auth/google/redirect" class="w-full flex items-center justify-center gap-3 bg-white border-2 border-primary text-primary hover:bg-primary hover:text-white py-4 rounded-xl text-base font-bold shadow-md transition-all duration-300 active:scale-[0.98]">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
                            </svg>
                            <span>Lanjutkan dengan Google</span>
                        </a>
                        
                        <div class="text-center w-full pt-4 border-t border-outline-variant/20">
                            <p class="text-sm text-on-surface-variant">Belum punya akun? <a class="text-primary font-bold hover:underline transition-all" href="/register">Daftar Sekarang</a></p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</x-layout>