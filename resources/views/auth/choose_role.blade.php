<x-layout title="Pilih Peran - Mataram Stay">
    <style>
        .role-card input:checked + label {
            border-color: #c2652a;
            background-color: #fbe8d8;
        }
    </style>

    <main class="flex-grow flex items-center justify-center min-h-[calc(100vh-8rem)] py-12 px-4 bg-background">
        <div class="bg-surface rounded-3xl shadow-xl border border-outline-variant/30 max-w-lg w-full p-8 md:p-10 space-y-8 animate-in fade-in duration-300">
            <!-- Header -->
            <div class="text-center space-y-2">
                <span class="material-symbols-outlined text-primary text-5xl bg-primary-container/10 p-3 rounded-2xl">account_circle</span>
                <h1 class="font-headline text-3xl font-medium text-on-surface tracking-tight mt-4">Pilih Peran Anda</h1>
                <p class="text-sm text-secondary font-body">Halo {{ $googleUser['name'] ?? 'Pengguna Google' }}, silakan tentukan tujuan Anda menggunakan Mataram Stay untuk melanjutkan.</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('auth.google.choose-role.save') }}" class="space-y-8">
                @csrf
                
                @if($errors->any())
                    <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
                        <ul class="list-disc list-inside text-xs font-bold space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    <!-- Seeker Card -->
                    <div class="role-card relative">
                        <input class="sr-only font-body" id="seeker" name="role" type="radio" value="seeker" checked>
                        <label class="flex flex-col items-center p-6 border-2 border-outline-variant/60 rounded-2xl cursor-pointer hover:border-primary/50 transition-all duration-300 h-full justify-center text-center" for="seeker">
                            <span class="material-symbols-outlined text-primary text-4xl mb-3">person_search</span>
                            <span class="text-base font-bold text-on-surface">Pencari Kos</span>
                            <span class="text-[11px] text-secondary mt-1 font-body leading-normal">Mencari dan menyewa kos ternyaman di Mataram</span>
                        </label>
                    </div>

                    <!-- Owner Card -->
                    <div class="role-card relative">
                        <input class="sr-only font-body" id="owner" name="role" type="radio" value="owner">
                        <label class="flex flex-col items-center p-6 border-2 border-outline-variant/60 rounded-2xl cursor-pointer hover:border-primary/50 transition-all duration-300 h-full justify-center text-center" for="owner">
                            <span class="material-symbols-outlined text-primary text-4xl mb-3">holiday_village</span>
                            <span class="text-base font-bold text-on-surface">Pemilik Kos</span>
                            <span class="text-[11px] text-secondary mt-1 font-body leading-normal">Memasarkan dan mengelola kos-kosan Anda</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-primary hover:bg-primary-container text-white py-4 rounded-xl font-label font-bold text-base shadow-md transition-all duration-300 flex items-center justify-center gap-2 active:scale-[0.98]">
                    <span>Selesaikan Pendaftaran</span>
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </button>
            </form>
        </div>
    </main>
</x-layout>
