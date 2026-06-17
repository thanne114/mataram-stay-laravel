<x-layout>
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 md:px-8 py-12">
        <!-- Dashboard Header -->
        <div class="border-b border-outline-variant/60 pb-6 mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-4xl md:text-5xl font-headline font-bold text-on-surface tracking-tight">Portal Admin</h1>
                <p class="text-secondary font-body mt-2">Kelola monetisasi, verifikasi identitas, dan persetujuan listing properti Mataram Stay.</p>
            </div>
            <div class="flex items-center gap-2 text-xs font-label text-secondary uppercase bg-surface-variant/40 px-3 py-1.5 rounded-full border border-outline-variant/30">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                <span>System Active</span>
            </div>
        </div>

        <!-- Global Status Messages -->
        @if(session('success'))
            <div class="mb-8 p-4 rounded-xl bg-primary/5 border border-primary/20 text-primary flex items-center gap-3 animate-in fade-in duration-300">
                <span class="material-symbols-outlined">check_circle</span>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="mb-8 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 animate-in fade-in duration-300">
                <ul class="list-disc list-inside text-sm font-bold space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-10">
            <!-- Platform Revenue -->
            <div class="bg-white rounded-2xl border border-outline-variant/30 p-5 md:p-6 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-label font-bold text-primary uppercase tracking-wider">Pendapatan Platform</span>
                    <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-xl">payments</span>
                </div>
                <div>
                    <h3 class="text-2xl md:text-3xl font-headline font-bold text-on-surface">Rp {{ number_format($totalRevenuePlatform, 0, ',', '.') }}</h3>
                    <p class="text-[11px] text-secondary font-body mt-1">Total komisi & biaya admin</p>
                </div>
            </div>

            <!-- Pending Seekers -->
            <div class="bg-white rounded-2xl border border-outline-variant/30 p-5 md:p-6 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-label font-bold text-secondary uppercase tracking-wider">Antrean Verifikasi</span>
                    <span class="material-symbols-outlined text-secondary bg-surface-variant p-2 rounded-xl">how_to_reg</span>
                </div>
                <div>
                    <h3 class="text-2xl md:text-3xl font-headline font-bold text-on-surface">{{ $pendingSeekersCount }}</h3>
                    <p class="text-[11px] text-secondary font-body mt-1">Pencari kos menunggu review</p>
                </div>
            </div>

            <!-- Draft Properties -->
            <div class="bg-white rounded-2xl border border-outline-variant/30 p-5 md:p-6 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-label font-bold text-secondary uppercase tracking-wider">Persetujuan Kos</span>
                    <span class="material-symbols-outlined text-secondary bg-surface-variant p-2 rounded-xl">apartment</span>
                </div>
                <div>
                    <h3 class="text-2xl md:text-3xl font-headline font-bold text-on-surface">{{ $draftPropertiesCount }}</h3>
                    <p class="text-[11px] text-secondary font-body mt-1">Listing kos baru (Draft)</p>
                </div>
            </div>

            <!-- Platform Total Users -->
            <div class="bg-white rounded-2xl border border-outline-variant/30 p-5 md:p-6 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-label font-bold text-secondary uppercase tracking-wider">Total Pengguna</span>
                    <span class="material-symbols-outlined text-secondary bg-surface-variant p-2 rounded-xl">group</span>
                </div>
                <div>
                    <h3 class="text-2xl md:text-3xl font-headline font-bold text-on-surface">{{ $totalSeekers + $totalOwners }}</h3>
                    <p class="text-[11px] text-secondary font-body mt-1">{{ $totalSeekers }} Pencari, {{ $totalOwners }} Pemilik</p>
                </div>
            </div>
        </div>

        <!-- Dashboard Tabs & Operations -->
        <div class="bg-white rounded-3xl border border-outline-variant/30 shadow-md overflow-hidden min-h-[500px]">
            <!-- Tab Navigation Header -->
            <div class="flex border-b border-outline-variant/60 bg-surface-variant/10 px-4 md:px-8">
                <button onclick="switchTab('monetization')" id="tab-btn-monetization" class="tab-btn px-4 md:px-6 py-5 font-label font-bold text-sm border-b-2 border-primary text-primary transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-xl">settings_input_component</span>
                    <span>Monetisasi</span>
                </button>
                <button onclick="switchTab('seekers')" id="tab-btn-seekers" class="tab-btn px-4 md:px-6 py-5 font-label font-medium text-sm border-b-2 border-transparent text-secondary hover:text-primary transition-all flex items-center gap-2 relative">
                    <span class="material-symbols-outlined text-xl">badge</span>
                    <span>Verifikasi Pencari</span>
                    @if($pendingSeekersCount > 0)
                        <span class="absolute top-3 right-0.5 md:right-1 bg-tertiary text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $pendingSeekersCount }}</span>
                    @endif
                </button>
                <button onclick="switchTab('properties')" id="tab-btn-properties" class="tab-btn px-4 md:px-6 py-5 font-label font-medium text-sm border-b-2 border-transparent text-secondary hover:text-primary transition-all flex items-center gap-2 relative">
                    <span class="material-symbols-outlined text-xl">real_estate_agent</span>
                    <span>Persetujuan Kos</span>
                    @if($draftPropertiesCount > 0)
                        <span class="absolute top-3 right-0.5 md:right-1 bg-tertiary text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $draftPropertiesCount }}</span>
                    @endif
                </button>
            </div>

            <!-- Tab Contents -->
            <div class="p-6 md:p-8">
                
                <!-- Tab 1: Monetization Settings & Revenues -->
                <div id="tab-content-monetization" class="tab-content space-y-10">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        <!-- Settings Form (5 cols on lg) -->
                        <div class="lg:col-span-5 bg-background/30 rounded-2xl border border-outline-variant/40 p-6">
                            <h3 class="text-xl font-headline font-bold text-on-surface mb-2">Parameter Monetisasi</h3>
                            <p class="text-xs text-secondary font-body mb-6">Sesuaikan potongan biaya admin platform dan persentase komisi secara global.</p>
                            
                            <form method="POST" action="{{ route('admin.update-settings') }}" class="space-y-6">
                                @csrf
                                <div class="flex flex-col gap-1.5">
                                    <label for="admin_fee" class="text-xs font-label font-bold text-secondary uppercase tracking-wider">Biaya Layanan Admin (Beban Pencari)</label>
                                    <div class="relative flex items-center">
                                        <span class="absolute left-4 text-secondary text-sm font-label font-bold">Rp</span>
                                        <input type="number" id="admin_fee" name="admin_fee" value="{{ $adminFee }}" class="w-full pl-11 pr-4 py-3 bg-white rounded-xl border border-outline-variant/60 focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body text-sm text-on-surface font-semibold" required>
                                    </div>
                                    <span class="text-[10px] text-secondary font-body">Biaya flat yang dikenakan kepada pencari kos per transaksi.</span>
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label for="commission_rate" class="text-xs font-label font-bold text-secondary uppercase tracking-wider">Tarif Komisi Platform (Beban Pemilik)</label>
                                    <div class="relative flex items-center">
                                        <input type="number" id="commission_rate" name="commission_rate" value="{{ $commissionRate }}" min="0" max="100" class="w-full pr-10 pl-4 py-3 bg-white rounded-xl border border-outline-variant/60 focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body text-sm text-on-surface font-semibold" required>
                                        <span class="absolute right-4 text-secondary text-sm font-label font-bold">%</span>
                                    </div>
                                    <span class="text-[10px] text-secondary font-body">Potongan persentase dari subtotal sewa kamar pemilik kos.</span>
                                </div>

                                <button type="submit" class="w-full bg-primary hover:bg-primary-container text-white py-3.5 rounded-xl font-label font-bold text-sm shadow-sm transition-all flex items-center justify-center gap-2 active:scale-[0.98]">
                                    <span class="material-symbols-outlined text-lg">save</span>
                                    Simpan Pengaturan
                                </button>
                            </form>
                        </div>

                        <!-- Revenue Breakdown (7 cols on lg) -->
                        <div class="lg:col-span-7 flex flex-col gap-6">
                            <div class="border-b border-outline-variant/40 pb-4">
                                <h3 class="text-xl font-headline font-bold text-on-surface">Rincian Keuangan Platform</h3>
                                <p class="text-xs text-secondary font-body mt-1">Data akumulasi pendapatan yang diperoleh platform secara real-time dari pemesanan yang sukses (Paid).</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-surface-variant/20 rounded-2xl border border-outline-variant/30 p-5">
                                    <span class="text-[10px] uppercase font-bold text-secondary tracking-wider block">Total Biaya Admin</span>
                                    <span class="text-2xl font-headline font-bold text-on-surface block mt-2">Rp {{ number_format($totalAdminFeesCollected, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-secondary font-body mt-1 block">Dari akumulasi Biaya Layanan</span>
                                </div>
                                <div class="bg-surface-variant/20 rounded-2xl border border-outline-variant/30 p-5">
                                    <span class="text-[10px] uppercase font-bold text-secondary tracking-wider block">Total Komisi Sewa</span>
                                    <span class="text-2xl font-headline font-bold text-on-surface block mt-2">Rp {{ number_format($totalCommissionsCollected, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-secondary font-body mt-1 block">Berdasarkan tarif komisi {{ $commissionRate }}%</span>
                                </div>
                            </div>

                            <div class="bg-primary/5 rounded-2xl border border-primary/20 p-6 flex items-start gap-4">
                                <div class="bg-primary/10 p-3 rounded-xl text-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-2xl">account_balance_wallet</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-label font-bold text-primary uppercase tracking-wider">Total Bersih Platform</h4>
                                    <span class="text-3xl font-headline font-bold text-on-surface block mt-1">Rp {{ number_format($totalRevenuePlatform, 0, ',', '.') }}</span>
                                    <p class="text-xs text-secondary font-body mt-2 leading-relaxed">Dana ini mewakili seluruh pendapatan platform yang diproses melalui sistem gerbang pembayaran otomatis. Potongan komisi telah dipisahkan secara otomatis sebelum dana ditarik oleh Pemilik Kos.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Pending Seekers Verification Queue -->
                <div id="tab-content-seekers" class="tab-content space-y-6 hidden">
                    <div class="border-b border-outline-variant/40 pb-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-headline font-bold text-on-surface">Validasi Identitas Pencari Kos</h3>
                            <p class="text-xs text-secondary font-body mt-1">Tinjau foto KTP dan selfie pencari kos secara seksama untuk memberikan status Verified.</p>
                        </div>
                        <span class="bg-surface-variant text-on-surface-variant text-xs font-bold font-label px-3 py-1 rounded-full border border-outline-variant/40">
                            {{ $pendingSeekers->count() }} Antrean
                        </span>
                    </div>

                    @if($pendingSeekers->isEmpty())
                        <div class="text-center py-16 bg-background/10 rounded-2xl border border-dashed border-outline-variant/50 flex flex-col items-center justify-center gap-3">
                            <span class="material-symbols-outlined text-4xl text-secondary">verified_user</span>
                            <h4 class="text-lg font-headline font-bold text-on-surface">Antrean Bersih!</h4>
                            <p class="text-xs text-secondary font-body">Semua dokumen identitas pencari kos telah diproses dan diverifikasi.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left font-body border-collapse">
                                <thead>
                                    <tr class="border-b border-outline-variant/60 text-secondary text-xs uppercase tracking-wider font-bold">
                                        <th class="py-4 px-3">Informasi Pencari</th>
                                        <th class="py-4 px-3">Jenis Identitas</th>
                                        <th class="py-4 px-3 text-center">Dokumen KTP / Paspor</th>
                                        <th class="py-4 px-3 text-center">Foto Selfie</th>
                                        <th class="py-4 px-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant/30 text-sm">
                                    @foreach($pendingSeekers as $seeker)
                                        <tr class="hover:bg-background/20 transition-colors">
                                            <td class="py-5 px-3">
                                                <div class="font-bold text-on-surface">{{ $seeker->name }}</div>
                                                <div class="text-xs text-secondary mt-0.5">{{ $seeker->email }}</div>
                                                <div class="text-[11px] text-primary font-semibold mt-1 flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-xs">chat</span>
                                                    <span>{{ $seeker->no_whatsapp ?? '-' }}</span>
                                                </div>
                                            </td>
                                            <td class="py-5 px-3 font-semibold uppercase text-secondary text-xs">
                                                {{ $seeker->identity_type ?? 'KTP' }}
                                            </td>
                                            <td class="py-5 px-3 text-center">
                                                @if($seeker->identity_photo)
                                                    <button onclick="openPhotoModal('{{ route('profile.identity-photo', ['filename' => basename($seeker->identity_photo)]) }}', 'KTP - {{ $seeker->name }}')" class="inline-flex items-center gap-1 text-primary hover:text-primary-container font-semibold text-xs border border-primary/20 bg-primary/5 px-2.5 py-1.5 rounded-lg transition-all">
                                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                                        <span>Lihat KTP</span>
                                                    </button>
                                                @else
                                                    <span class="text-xs text-secondary italic">Tidak diunggah</span>
                                                @endif
                                            </td>
                                            <td class="py-5 px-3 text-center">
                                                @if($seeker->selfie_photo)
                                                    <button onclick="openPhotoModal('{{ route('profile.identity-photo', ['filename' => basename($seeker->selfie_photo)]) }}', 'Selfie - {{ $seeker->name }}')" class="inline-flex items-center gap-1 text-primary hover:text-primary-container font-semibold text-xs border border-primary/20 bg-primary/5 px-2.5 py-1.5 rounded-lg transition-all">
                                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                                        <span>Lihat Selfie</span>
                                                    </button>
                                                @else
                                                    <span class="text-xs text-secondary italic">Tidak diunggah</span>
                                                @endif
                                            </td>
                                            <td class="py-5 px-3 text-right">
                                                <form method="POST" action="{{ route('admin.verify-seeker', $seeker->id) }}">
                                                    @csrf
                                                    <button type="submit" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-xl text-xs font-label font-bold transition-all shadow-sm flex items-center justify-center gap-1 inline-flex active:scale-95">
                                                        <span class="material-symbols-outlined text-sm">verified</span>
                                                        Verifikasi
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Tab 3: Property Approval Queue -->
                <div id="tab-content-properties" class="tab-content space-y-6 hidden">
                    <div class="border-b border-outline-variant/40 pb-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-headline font-bold text-on-surface">Persetujuan Listing Kos Baru</h3>
                            <p class="text-xs text-secondary font-body mt-1">Tinjau deskripsi, fasilitas, alamat, dan dokumentasi kos baru sebelum dipublikasikan.</p>
                        </div>
                        <span class="bg-surface-variant text-on-surface-variant text-xs font-bold font-label px-3 py-1 rounded-full border border-outline-variant/40">
                            {{ $draftProperties->count() }} Menunggu
                        </span>
                    </div>

                    @if($draftProperties->isEmpty())
                        <div class="text-center py-16 bg-background/10 rounded-2xl border border-dashed border-outline-variant/50 flex flex-col items-center justify-center gap-3">
                            <span class="material-symbols-outlined text-4xl text-secondary">apartment</span>
                            <h4 class="text-lg font-headline font-bold text-on-surface">Tidak ada kos yang pending</h4>
                            <p class="text-xs text-secondary font-body">Seluruh listing kos baru telah ditinjau dan diterbitkan.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($draftProperties as $prop)
                                <div class="bg-background/25 rounded-2xl border border-outline-variant/40 overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow">
                                    <div class="p-6 space-y-4">
                                        <div class="flex items-start gap-4">
                                            <div class="w-20 h-20 rounded-xl overflow-hidden border border-outline-variant/30 flex-shrink-0">
                                                @if($prop->main_image)
                                                    <img src="{{ asset('storage/' . $prop->main_image) }}" class="w-full h-full object-cover" alt="Main Image">
                                                @else
                                                    <div class="w-full h-full bg-surface-variant flex items-center justify-center">
                                                        <span class="material-symbols-outlined text-secondary">image</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <span class="bg-tertiary-fixed text-on-tertiary-fixed-variant text-[10px] font-bold tracking-widest uppercase px-2 py-0.5 rounded-full border border-tertiary/10">
                                                    {{ $prop->type }}
                                                </span>
                                                <h4 class="font-headline text-xl font-bold text-on-surface mt-1 leading-tight">{{ $prop->name }}</h4>
                                                <p class="text-xs text-secondary mt-1 flex items-center gap-0.5">
                                                    <span class="material-symbols-outlined text-sm">location_on</span>
                                                    <span>{{ $prop->area }}, Mataram</span>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="text-xs text-on-surface-variant font-body line-clamp-3 leading-relaxed bg-white/50 p-3 rounded-lg border border-outline-variant/20 font-light">
                                            {{ $prop->description }}
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 text-xs">
                                            <div class="bg-white p-2 rounded-lg border border-outline-variant/20">
                                                <span class="text-[10px] text-secondary font-bold uppercase tracking-wider block">Pemilik Kos</span>
                                                <span class="font-bold text-on-surface mt-0.5 block">{{ $prop->owner->name ?? 'Juragan Kos' }}</span>
                                            </div>
                                            <div class="bg-white p-2 rounded-lg border border-outline-variant/20">
                                                <span class="text-[10px] text-secondary font-bold uppercase tracking-wider block">No. WhatsApp</span>
                                                <span class="font-bold text-primary mt-0.5 block">{{ $prop->owner->no_whatsapp ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-surface-variant/10 border-t border-outline-variant/40 px-6 py-4 flex items-center justify-between gap-4">
                                        <a href="{{ route('property.show', $prop->slug) }}" target="_blank" class="text-xs text-primary hover:text-primary-container font-label font-bold flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">open_in_new</span>
                                            Pratinjau Halaman Detail
                                        </a>
                                        <div class="flex items-center gap-2">
                                            <button type="button" onclick="openRejectModal('{{ $prop->id }}', '{{ addslashes($prop->name) }}')" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-4 py-2 rounded-xl text-xs font-label font-bold transition-all shadow-sm flex items-center gap-1 active:scale-95">
                                                <span class="material-symbols-outlined text-sm">cancel</span>
                                                Tolak
                                            </button>
                                            <form method="POST" action="{{ route('admin.approve-property', $prop->id) }}">
                                                @csrf
                                                <button type="submit" class="bg-primary hover:bg-primary-container text-white px-4 py-2 rounded-xl text-xs font-label font-bold transition-all shadow-sm flex items-center gap-1 active:scale-95">
                                                    <span class="material-symbols-outlined text-sm">publish</span>
                                                    Setujui & Terbitkan
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <!-- Document Viewer Modal -->
        <div id="photo-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-on-background/70 backdrop-blur-sm hidden" onclick="closePhotoModal()">
            <div class="bg-white rounded-3xl p-6 max-w-2xl w-full mx-4 shadow-2xl relative border border-outline-variant" onclick="event.stopPropagation()">
                <div class="flex justify-between items-center pb-3 border-b border-outline-variant mb-4">
                    <h3 id="photo-modal-title" class="font-headline text-xl font-bold text-on-surface">Dokumen Pendukung</h3>
                    <button onclick="closePhotoModal()" class="text-secondary hover:text-on-surface transition-colors flex items-center justify-center p-1 rounded-full bg-surface-variant/50">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="w-full aspect-[4/3] rounded-2xl overflow-hidden bg-background/50 border border-outline-variant/60 relative">
                    <div id="photo-modal-loader" class="absolute inset-0 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined animate-spin text-3xl">sync</span>
                    </div>
                    <img id="photo-modal-img" src="" class="w-full h-full object-contain hidden" alt="Dokumen Identitas" onload="document.getElementById('photo-modal-loader').classList.add('hidden'); this.classList.remove('hidden');">
                </div>
                <div class="mt-4 flex justify-end">
                    <button onclick="closePhotoModal()" class="bg-surface-variant hover:bg-outline-variant/60 text-on-surface px-6 py-2 rounded-xl font-label font-bold text-xs transition-all">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- Rejection Modal -->
        <div id="reject-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-on-background/70 backdrop-blur-sm hidden" onclick="closeRejectModal()">
            <div class="bg-white rounded-3xl p-6 max-w-md w-full mx-4 shadow-2xl relative border border-outline-variant" onclick="event.stopPropagation()">
                <div class="flex justify-between items-center pb-3 border-b border-outline-variant mb-4">
                    <h3 class="font-headline text-xl font-bold text-on-surface">Tolak Properti</h3>
                    <button onclick="closeRejectModal()" class="text-secondary hover:text-on-surface transition-colors flex items-center justify-center p-1 rounded-full bg-surface-variant/50">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form id="reject-form" method="POST" action="" class="space-y-4">
                    @csrf
                    <div>
                        <p class="text-sm text-secondary font-body mb-3">
                            Anda akan menolak pengajuan kos <strong id="reject-property-name" class="text-on-surface"></strong>. Silakan masukkan alasan penolakan (minimal 10 karakter):
                        </p>
                        <textarea id="rejection_reason" name="rejection_reason" rows="4" class="w-full p-4 bg-white rounded-xl border border-outline-variant/60 focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none font-body text-sm text-on-surface" placeholder="Contoh: Alamat kurang lengkap atau foto properti buram..." required></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeRejectModal()" class="bg-surface-variant hover:bg-outline-variant/60 text-on-surface px-5 py-2.5 rounded-xl font-label font-bold text-xs transition-all">
                            Batal
                        </button>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-label font-bold text-xs transition-all shadow-sm flex items-center gap-1 active:scale-95">
                            <span class="material-symbols-outlined text-sm">send</span>
                            Kirim Penolakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function switchTab(tabId) {
            // Hide all tab content
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Remove active style from all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-primary', 'text-primary');
                btn.classList.add('border-transparent', 'text-secondary');
                btn.querySelector('span:not(.material-symbols-outlined)').classList.remove('font-bold');
                btn.querySelector('span:not(.material-symbols-outlined)').classList.add('font-medium');
            });

            // Show target tab content
            document.getElementById('tab-content-' + tabId).classList.remove('hidden');
            // Add active style to target tab button
            const activeBtn = document.getElementById('tab-btn-' + tabId);
            activeBtn.classList.remove('border-transparent', 'text-secondary');
            activeBtn.classList.add('border-primary', 'text-primary');
            activeBtn.querySelector('span:not(.material-symbols-outlined)').classList.remove('font-medium');
            activeBtn.querySelector('span:not(.material-symbols-outlined)').classList.add('font-bold');

            // Save active tab in localStorage
            localStorage.setItem('admin_active_tab', tabId);
        }

        function openPhotoModal(imgUrl, title) {
            const modal = document.getElementById('photo-modal');
            const modalImg = document.getElementById('photo-modal-img');
            const modalTitle = document.getElementById('photo-modal-title');
            const loader = document.getElementById('photo-modal-loader');

            modalTitle.innerText = title;
            modalImg.classList.add('hidden');
            loader.classList.remove('hidden');
            modalImg.src = imgUrl;
            modal.classList.remove('hidden');
        }

        function closePhotoModal() {
            document.getElementById('photo-modal').classList.add('hidden');
            document.getElementById('photo-modal-img').src = '';
        }

        function openRejectModal(propertyId, propertyName) {
            const modal = document.getElementById('reject-modal');
            const form = document.getElementById('reject-form');
            const nameSpan = document.getElementById('reject-property-name');
            const reasonInput = document.getElementById('rejection_reason');

            nameSpan.innerText = propertyName;
            reasonInput.value = '';
            
            // Set action URL dynamically
            form.action = `/admin/property/${propertyId}/reject`;
            modal.classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
        }

        // Restore active tab on load
        document.addEventListener('DOMContentLoaded', () => {
            const savedTab = localStorage.getItem('admin_active_tab') || 'monetization';
            switchTab(savedTab);
        });
    </script>
</x-layout>
