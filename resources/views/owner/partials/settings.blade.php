        <section id="settings" class="tab-content space-y-12 hidden animate-in fade-in duration-500">
            <div class="border-b border-outline/10 pb-6">
                <h1 class="text-4xl font-headline italic tracking-tight text-on-surface">Pengaturan Akun</h1>
                <p class="text-secondary font-body mt-2">Kelola informasi pribadi dan preferensi keamanan Anda.</p>
            </div>
            
            {{-- Alert Success & Error --}}
            @if(session('success'))
            <div class="p-4 rounded-xl bg-primary/5 border border-primary/20 text-primary flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
            @endif
            @if($errors->any())
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
                <ul class="list-disc list-inside text-sm font-bold space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-4 animate-in fade-in duration-700 accordion-item">
                    <button type="button" class="w-full flex items-center justify-between p-4 bg-surface-container-low rounded-xl border border-outline/5 sahara-shadow group transition-all hover:bg-surface-container-high" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.chevron').classList.toggle('rotate-180')">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary" data-icon="person">person</span>
                            <h3 class="text-2xl font-headline">Informasi Pribadi</h3>
                        </div>
                        <span class="material-symbols-outlined text-secondary transition-transform duration-300 chevron">expand_more</span>
                    </button>
                    
                    <div class="bg-surface-container-low rounded-xl p-8 sahara-shadow border border-outline/5 flex flex-col md:flex-row gap-10">
                        <div class="flex flex-col items-center gap-4">
                            <div class="relative group">
                                <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-md">
                                    <img id="profile_photo_preview" alt="Profile photo" class="w-full h-full object-cover" src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=c2652a&color=fff' }}">
                                </div>
                                <button type="button" id="upload_photo_btn" class="absolute bottom-1 right-1 bg-primary text-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform active:scale-95">
                                    <span class="material-symbols-outlined text-sm leading-none" data-icon="photo_camera">photo_camera</span>
                                </button>
                                <input type="file" name="profile_photo" id="profile_photo_input" class="hidden" accept="image/*">
                            </div>
                            <p class="text-xs text-secondary font-body text-center max-w-[120px]">JPG, PNG, WEBP. Maksimal 2MB.</p>
                        </div>
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nama Lengkap</label>
                                <input name="name" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="text" value="{{ old('name', auth()->user()->name) }}" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Username</label>
                                <input name="username" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="text" value="{{ old('username', auth()->user()->username) }}">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nomor WhatsApp</label>
                                <input name="no_whatsapp" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="tel" value="{{ old('no_whatsapp', auth()->user()->no_whatsapp) }}" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Email</label>
                                <div class="relative">
                                    <input name="email" id="profile_email_field" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm pr-32" type="email" value="{{ old('email', auth()->user()->email) }}" required>
                                    @if(auth()->user()->email_verified_at)
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 bg-green-50 text-green-700 text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1 border border-green-200">
                                            <span class="material-symbols-outlined text-xs" style="font-variation-settings: 'FILL' 1;">verified</span>
                                            VERIFIED
                                        </span>
                                    @else
                                        <button type="button" onclick="sendEmailOtpAndOpenModal()" class="absolute right-3 top-1/2 -translate-y-1/2 bg-amber-50 text-amber-700 hover:bg-amber-100 text-[10px] font-bold px-2 py-1.5 rounded flex items-center gap-1 border border-amber-200 transition-all active:scale-95">
                                            <span class="material-symbols-outlined text-xs" style="font-variation-settings: 'FILL' 0;">mail</span>
                                            VERIFIKASI
                                        </button>
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Additional decorative/SPA dummy fields --}}
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Jenis Kelamin</label>
                                <select class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm">
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="space-y-2" style="transition: transform 0.2s;">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Tanggal Lahir</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="date">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Pekerjaan</label>
                                <select class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" onchange="const parent = this.closest('.grid'); parent.querySelectorAll('.job-conditional').forEach(el => el.classList.add('hidden')); if(this.value === 'Mahasiswa') parent.querySelector('.job-mahasiswa').classList.remove('hidden'); if(this.value === 'Karyawan') parent.querySelector('.job-karyawan').classList.remove('hidden'); if(this.value === 'Lainnya') parent.querySelector('.job-lainnya').classList.remove('hidden');">
                                    <option value="">Pilih Pekerjaan</option>
                                    <option value="Mahasiswa">Mahasiswa</option>
                                    <option value="Karyawan">Karyawan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="space-y-2 job-conditional job-mahasiswa hidden">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nama Kampus/Sekolah</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" placeholder="Contoh: Universitas Mataram" type="text">
                            </div>
                            <div class="space-y-2 job-conditional job-karyawan hidden">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nama Instansi</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" placeholder="Contoh: PT. Maju Bersama" type="text">
                            </div>
                            <div class="space-y-2 job-conditional job-lainnya hidden" style="transition: transform 0.2s;">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Deskripsi Pekerjaan</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" placeholder="Jelaskan pekerjaan Anda" type="text">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Kota Asal</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" placeholder="Contoh: Mataram" type="text">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Status Pendidikan Terakhir</label>
                                <select class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm">
                                    <option value="SMA/SMK">SMA/SMK</option>
                                    <option value="Diploma">Diploma</option>
                                    <option value="Sarjana">Sarjana</option>
                                    <option value="Pascasarjana">Pascasarjana</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nomor Darurat</label>
                                <input class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" placeholder="Nomor telepon keluarga/kerabat" type="tel">
                            </div>
                            
                            <div class="md:col-span-2 flex justify-end mt-4">
                                <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl font-body text-sm font-bold shadow-md hover:bg-primary-container hover:text-on-primary-container transition-all active:scale-95">Simpan Perubahan</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Accordion: Informasi Rekening Pencairan --}}
                <div class="space-y-4 accordion-item mt-6">
                    <button type="button" class="w-full flex items-center justify-between p-4 bg-surface-container-low rounded-xl border border-outline/5 sahara-shadow group transition-all hover:bg-surface-container-high" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.chevron').classList.toggle('rotate-180')">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">account_balance</span>
                            <h3 class="text-2xl font-headline">Informasi Rekening Pencairan</h3>
                        </div>
                        <span class="material-symbols-outlined text-secondary transition-transform duration-300 chevron">expand_more</span>
                    </button>
                    
                    <div class="hidden bg-surface-container-low rounded-xl p-8 sahara-shadow border border-outline/5 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nama Bank</label>
                            <select name="bank_name" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm">
                                <option value="">Pilih Bank</option>
                                <option value="BCA" {{ old('bank_name', auth()->user()->bank_name) == 'BCA' ? 'selected' : '' }}>BCA</option>
                                <option value="Mandiri" {{ old('bank_name', auth()->user()->bank_name) == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                <option value="BNI" {{ old('bank_name', auth()->user()->bank_name) == 'BNI' ? 'selected' : '' }}>BNI</option>
                                <option value="BRI" {{ old('bank_name', auth()->user()->bank_name) == 'BRI' ? 'selected' : '' }}>BRI</option>
                                <option value="BSI" {{ old('bank_name', auth()->user()->bank_name) == 'BSI' ? 'selected' : '' }}>BSI</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nomor Rekening</label>
                            <input name="bank_account_number" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="text" placeholder="Contoh: 1234567890" value="{{ old('bank_account_number', auth()->user()->bank_account_number) }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-secondary">Nama Pemilik Rekening</label>
                            <input name="bank_account_name" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm" type="text" placeholder="Nama sesuai buku tabungan" value="{{ old('bank_account_name', auth()->user()->bank_account_name) }}">
                        </div>
                    </div>
                </div>

                <div class="space-y-4 accordion-item">
                    <button type="button" class="w-full flex items-center justify-between p-4 bg-surface-container-low rounded-xl border border-outline/5 sahara-shadow group transition-all hover:bg-surface-container-high" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.chevron').classList.toggle('rotate-180')">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary" data-icon="lock">lock</span>
                            <h3 class="text-2xl font-headline">Keamanan &amp; Password</h3>
                        </div>
                        <span class="material-symbols-outlined text-secondary transition-transform duration-300 chevron">expand_more</span>
                    </button>
                    
                    <div class="hidden bg-surface-container-low rounded-xl p-8 sahara-shadow border border-outline/5 grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Password Saat Ini</label>
                                <div class="relative group">
                                    <input name="current_password" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm pr-12 password-input" placeholder="••••••••" type="password">
                                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary hover:text-primary p-1 focus:outline-none toggle-password" onclick="togglePasswordVisibility(this)" type="button">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Password Baru</label>
                                <div class="relative group">
                                    <input name="password" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm pr-12 password-input" placeholder="••••••••" type="password">
                                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary hover:text-primary p-1 focus:outline-none toggle-password" onclick="togglePasswordVisibility(this)" type="button">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-secondary">Konfirmasi Password</label>
                                <div class="relative group">
                                    <input name="password_confirmation" class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 focus:ring-1 focus:ring-primary focus:border-primary outline-none font-body text-sm pr-12 password-input" placeholder="••••••••" type="password">
                                    <button class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary hover:text-primary p-1 focus:outline-none toggle-password" onclick="togglePasswordVisibility(this)" type="button">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg font-body text-sm font-bold shadow-md hover:bg-primary-container hover:text-on-primary-container transition-all active:scale-95">Update Password</button>
                        </div>
                        <div class="flex flex-col justify-between p-6 bg-surface-container rounded-xl border border-outline-variant/30">
                            <div class="space-y-3">
                                <h4 class="font-headline text-xl">Two-Factor Authentication</h4>
                                <p class="text-sm text-secondary font-body leading-relaxed">Berikan perlindungan ekstra untuk akun Anda. Kode verifikasi akan dikirimkan ke nomor WhatsApp terdaftar.</p>
                            </div>
                            <div class="mt-8 flex items-center justify-between">
                                <span class="text-sm font-bold text-on-surface">Status: <span class="text-primary">Aktif</span></span>
                                <label class="switch">
                                    <input checked="" type="checkbox" disabled>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <!-- Active Sessions -->
            <div class="space-y-4 accordion-item">
                <button class="w-full flex items-center justify-between p-4 bg-surface-container-low rounded-xl border border-outline/5 sahara-shadow group transition-all hover:bg-surface-container-high" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.chevron').classList.toggle('rotate-180')">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary" data-icon="devices">devices</span>
                        <h3 class="text-2xl font-headline">Sesi Aktif</h3>
                    </div>
                    <span class="material-symbols-outlined text-secondary transition-transform duration-300 chevron">expand_more</span>
                </button>
                <div class="hidden bg-surface-container-low rounded-xl overflow-hidden sahara-shadow border border-outline/5">
                    <div class="divide-y divide-outline/10">
                        <div class="p-6 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-surface-container-highest rounded-full flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined" data-icon="laptop_mac">laptop_mac</span>
                                </div>
                                <div>
                                    <p class="font-bold text-sm">Windows Laptop • Mataram, NTB</p>
                                    <p class="text-xs text-secondary">Browser Chrome • Sesi Saat Ini</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-primary px-3 py-1 bg-primary/5 rounded-lg border border-primary/20">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Danger Zone: Deactivation -->
            <div class="pt-8 border-t border-outline/10 accordion-item">
                <div class="mt-4 bg-tertiary/5 border border-tertiary/10 rounded-xl p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="space-y-2 text-center md:text-left">
                        <h4 class="font-headline text-xl text-tertiary">Nonaktifkan Akun</h4>
                        <p class="text-sm text-secondary font-body">Tindakan ini tidak dapat dibatalkan. Semua data properti dan riwayat sewa Anda akan dihapus permanen.</p>
                    </div>
                    <form action="{{ route('profile.deactivate') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan akun secara permanen? Semua data Anda (properti, transaksi, chat) akan dihapus secara permanen dari sistem.');" class="shrink-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-tertiary text-white px-8 py-3 rounded-lg font-body text-sm font-bold shadow-md hover:bg-tertiary-container transition-all active:scale-95">Deactivate Account</button>
                    </form>
                </div>
            </div>
        </section>
