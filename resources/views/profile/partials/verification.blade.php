        <div id="view-verifikasi" class="view-section hidden space-y-6">
            <div class="bg-white border border-outline/10 rounded-xl p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#1e293b] border-b border-outline/10 pb-4 mb-4">Email and Phone</h2>
                
                <div class="mb-6">
                    <h3 class="text-primary font-medium text-sm mb-1">Mengapa Verifikasi Penting?</h3>
                    <p class="text-sm text-[#64748b]">Verifikasi bisa mencegah akun kamu diretas oleh orang lain. Karena untuk mengakses akun tetap membutuhkan kode verifikasi yang hanya diketahui oleh Anda.</p>
                </div>
                
                <div class="flex items-center justify-between py-4">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-primary text-3xl">mail</span>
                        <div>
                            <p class="font-medium text-[15px] text-[#1e293b]">Email</p>
                            <p class="text-sm text-[#94a3b8]">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <button class="bg-primary text-white px-6 py-2 rounded-md font-medium text-sm hover:bg-primary-container transition">Ubah</button>
                </div>
                
                <div class="flex items-center justify-between py-4 border-t border-outline/5">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-primary text-3xl">call</span>
                        <div>
                            <p class="font-medium text-[15px] text-[#1e293b]">Nomor Handphone</p>
                            <p class="text-sm text-[#94a3b8]">{{ auth()->user()->no_whatsapp ?? 'Belum verifikasi' }}</p>
                        </div>
                    </div>
                    @if(auth()->user()->isPhoneVerified())
                        <span class="flex items-center gap-1 text-green-600 bg-green-50 px-3 py-1.5 rounded-lg text-xs font-bold border border-green-200">
                            <span class="material-symbols-outlined text-sm">verified</span> Terverifikasi
                        </span>
                    @else
                        <button type="button" onclick="openOtpModal()" class="bg-primary text-white px-6 py-2 rounded-md font-medium text-sm hover:bg-primary-container transition">Verifikasi</button>
                    @endif
                </div>
            </div>

            <div class="bg-white border border-outline/10 rounded-xl p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#1e293b] border-b border-outline/10 pb-4 mb-4">Verifikasi Identitas</h2>
                
                @if(auth()->user()->isIdentityVerified())
                    <div class="bg-green-50 border border-green-200 p-4 rounded-xl mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-green-600 text-3xl">verified</span>
                        <div>
                            <p class="text-green-800 font-bold text-sm">Identitas Terverifikasi</p>
                            <p class="text-green-700 text-xs font-body">Selamat! Identitas Anda ({{ strtoupper(auth()->user()->identity_type) }}) telah berhasil diverifikasi oleh sistem.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6 mt-4">
                        <div>
                            <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-2">Foto Identitas</p>
                            <img src="{{ route('profile.identity-photo', basename(auth()->user()->identity_photo)) }}" class="w-full h-48 object-cover rounded-lg border border-outline-variant/50 shadow-sm" alt="Foto Identitas">
                        </div>
                        <div>
                            <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-2">Swafoto Identitas</p>
                            <img src="{{ route('profile.identity-photo', basename(auth()->user()->selfie_photo)) }}" class="w-full h-48 object-cover rounded-lg border border-outline-variant/50 shadow-sm" alt="Swafoto Identitas">
                        </div>
                    </div>
                @elseif(!empty(auth()->user()->identity_photo))
                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl mb-6 flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
                        <span class="material-symbols-outlined text-blue-600 text-3xl">schedule</span>
                        <div>
                            <p class="text-blue-800 font-bold text-sm">Dokumen Sedang Ditinjau</p>
                            <p class="text-blue-700 text-xs font-body">Dokumen sedang ditinjau. Menunggu verifikasi dari Administrator.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6 mt-4">
                        <div>
                            <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-2">Foto Identitas</p>
                            <img src="{{ route('profile.identity-photo', basename(auth()->user()->identity_photo)) }}" class="w-full h-48 object-cover rounded-lg border border-outline-variant/50 shadow-sm" alt="Foto Identitas">
                        </div>
                        <div>
                            <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-2">Swafoto Identitas</p>
                            <img src="{{ route('profile.identity-photo', basename(auth()->user()->selfie_photo)) }}" class="w-full h-48 object-cover rounded-lg border border-outline-variant/50 shadow-sm" alt="Swafoto Identitas">
                        </div>
                    </div>
                @else
                    <form action="{{ route('profile.verify-identity') }}" method="POST" enctype="multipart/form-data" id="identity_form">
                        @csrf
                        <div class="bg-primary/5 border border-primary/10 p-4 rounded-md mb-6">
                            <p class="text-primary font-medium text-sm mb-1">Lengkapi datamu agar proses pengajuan sewa lebih cepat.</p>
                            <p class="text-primary/80 text-sm">Kami melindungi informasi dan penggunaan data diri para pengguna kami.</p>
                        </div>
                        
                        <div class="flex items-center gap-6 mb-8">
                            <span class="text-sm font-medium text-[#1e293b] w-32 font-bold">Jenis Identitas</span>
                            <div class="flex gap-8">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="identity_type" value="ktp" checked class="text-primary focus:ring-primary h-4 w-4 border-gray-300">
                                    <span class="text-sm text-[#475569]">eKTP</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="identity_type" value="sim" class="text-primary focus:ring-primary h-4 w-4 border-gray-300">
                                    <span class="text-sm text-[#475569]">SIM</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="identity_type" value="passport" class="text-primary focus:ring-primary h-4 w-4 border-gray-300">
                                    <span class="text-sm text-[#475569]">Passport</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-6 mb-4">
                            <span class="text-sm font-medium text-[#1e293b] w-32 shrink-0 font-bold">Upload Foto Identitas</span>
                            <div class="flex gap-4 w-full">
                                <div onclick="document.getElementById('identity_photo_input').click()" class="border border-dashed border-[#cbd5e1] rounded-lg p-6 flex flex-col items-center justify-center w-1/2 text-center cursor-pointer hover:bg-gray-50 transition relative min-h-[140px]" id="identity_dropzone">
                                    <div id="identity_preview_container" class="hidden absolute inset-0 w-full h-full bg-white rounded-lg overflow-hidden">
                                        <img src="" id="identity_preview" class="w-full h-full object-cover">
                                    </div>
                                    <span class="material-symbols-outlined text-primary text-4xl mb-2">badge</span>
                                    <p class="text-primary font-medium text-sm">Kartu Identitas</p>
                                </div>
                                <input type="file" name="identity_photo" id="identity_photo_input" class="hidden" accept="image/*" required>
                                
                                <div onclick="document.getElementById('selfie_photo_input').click()" class="border border-dashed border-[#cbd5e1] rounded-lg p-6 flex flex-col items-center justify-center w-1/2 text-center cursor-pointer hover:bg-gray-50 transition relative min-h-[140px]" id="selfie_dropzone">
                                    <div id="selfie_preview_container" class="hidden absolute inset-0 w-full h-full bg-white rounded-lg overflow-hidden">
                                        <img src="" id="selfie_preview" class="w-full h-full object-cover">
                                    </div>
                                    <span class="material-symbols-outlined text-primary text-4xl mb-2">photo_camera</span>
                                    <p class="text-primary font-medium text-sm">Selfie dengan Kartu Identitas</p>
                                </div>
                                <input type="file" name="selfie_photo" id="selfie_photo_input" class="hidden" accept="image/*" required>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 text-error ml-0 md:ml-[150px] mb-6" id="verification_error_msg">
                            <span class="material-symbols-outlined text-base">info</span>
                            <span class="text-[13px]">Kamu belum mengunggah foto kartu identitas dan swafoto</span>
                        </div>
                        
                        <div class="ml-0 md:ml-[150px] space-y-4">
                            <label class="flex items-start gap-2 cursor-pointer">
                                <input type="checkbox" id="agree_verification" class="mt-1 rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-[13px] text-[#64748b] leading-tight">Dengan melanjutkan, saya menjamin data yang diberikan adalah<br>benar dan menyetujui <a href="#" class="text-primary font-medium hover:underline">privacy policy</a></span>
                            </label>
                            <button type="submit" id="btn_submit_verification" class="bg-[#cbd5e1] text-white px-10 py-2.5 rounded-md font-medium text-sm cursor-not-allowed transition-all" disabled>Simpan</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
