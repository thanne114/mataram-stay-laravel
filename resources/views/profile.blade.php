<!DOCTYPE html>
<html class="light" lang="id" style="">
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Seeker Portal | Mataram Stay</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Pusher & Echo via CDN -->
    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.0/dist/echo.iife.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Manrope:wght@200..800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                "outline-variant": "#d8d0c8",
                "background": "#faf5ee",
                "surface": "#faf5ee",
                "on-surface": "#3a302a",
                "primary": "#c2652a",
                "on-primary": "#ffffff",
                "primary-container": "#e08850",
                "secondary": "#78706a",
                "surface-bright": "#faf5ee",
                "surface-variant": "#ece6dc",
                "on-background": "#3a302a",
                "tertiary": "#8c3c3c",
                "tertiary-fixed": "#fce0e0",
                "on-tertiary-fixed-variant": "#6e3030",
                "error": "#c0392b",
                "surface-container-low": "#f6f0e8",
                "surface-container": "#f2ece4",
                "surface-container-high": "#ece6dc",
                "surface-container-highest": "#e6e0d6",
                "outline": "#9a9088",
                "surface-container-lowest": "#ffffff"
            },
            "borderRadius": {
                "DEFAULT": "0.25rem",
                "lg": "0.5rem",
                "xl": "0.75rem",
                "full": "9999px"
            },
            "fontFamily": {
                "headline": ["Eb Garamond"],
                "display": ["Eb Garamond"],
                "body": ["Manrope"],
                "label": ["Manrope"]
            }
          },
        },
      }
    </script>
    <style>
        body { font-family: 'Manrope', sans-serif; background-color: #faf5ee; color: #3a302a; }
        h1, h2, h3, h4 { font-family: 'Eb Garamond', serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .sahara-shadow { box-shadow: 0 2px 16px rgba(58, 48, 42, 0.04); }
        .active-nav-border { border-bottom: 2px solid #c2652a; }
        
        /* Custom Switch */
        .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #d8d0c8; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: #c2652a; }
        input:checked + .slider:before { transform: translateX(20px); }
    </style>
</head>
@php
    $hasPendingTransaction = $bookings->where('status', 'Pending')->isNotEmpty();
@endphp

<body class="bg-background text-on-surface selection:bg-primary/20 flex flex-col min-h-screen">

<x-navbar />

<main class="max-w-screen-2xl mx-auto px-6 lg:px-12 py-10 lg:py-16 flex flex-col lg:flex-row gap-12 flex-grow w-full">
    
    <aside class="w-full lg:w-64 flex flex-col gap-2 shrink-0">
        <div class="mb-6 px-4">
            <h2 class="text-xl font-display font-semibold text-on-surface">Ruang Penyewa</h2>
            <p class="text-xs text-secondary font-body">Pusat kendali akun dan riwayat pesanan Anda.</p>
        </div>
        <nav class="flex flex-col gap-1" id="sidebar-nav">
            <a href="#" onclick="switchView('view-transaksi', this)" class="nav-link flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group">
                <span class="material-symbols-outlined text-xl group-hover:text-primary">receipt_long</span>
                Transaksi
                @if($hasPendingTransaction)
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse ml-1"></span>
                @endif
            </a>
            <a href="#" onclick="switchView('view-pesan', this)" class="nav-link flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group">
                <span class="material-symbols-outlined text-xl group-hover:text-primary">forum</span>
                Pesan
            </a>
            <a href="#" onclick="switchView('view-kossaya', this)" class="nav-link flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group">
                <span class="material-symbols-outlined text-xl group-hover:text-primary">apartment</span>
                Kos Saya
            </a>
            <a href="#" onclick="switchView('view-riwayatpengajuan', this)" class="nav-link flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group">
                <span class="material-symbols-outlined text-xl group-hover:text-primary">list_alt</span>
                Riwayat Pengajuan Sewa
            </a>
            <a href="#" onclick="switchView('view-riwayatkos', this)" class="nav-link flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group">
                <span class="material-symbols-outlined text-xl group-hover:text-primary">history</span>
                Riwayat Kos
            </a>
            <a href="#" onclick="switchView('view-verifikasi', this)" class="nav-link flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group">
                <span class="material-symbols-outlined text-xl group-hover:text-primary">verified_user</span>
                Verifikasi Akun
                @if(!auth()->user()->is_verified)
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse ml-1"></span>
                @endif
            </a>
            <a href="#" onclick="switchView('view-settings', this)" class="nav-link flex items-center gap-3 bg-primary-container text-on-primary-container rounded-lg px-4 py-3 font-semibold font-body text-sm shadow-sm group">
                <span class="material-symbols-outlined text-xl group-hover:text-on-primary-container">settings</span>
                Settings
            </a>
            <div class="mt-8 pt-4 border-t border-outline/10">
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 text-tertiary hover:bg-tertiary/10 rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group w-full text-left">
                        <span class="material-symbols-outlined text-xl">logout</span>
                        Keluar (Logout)
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <section class="flex-1 max-w-4xl">

        @if(!auth()->user()->is_verified)
            @if(empty(auth()->user()->identity_photo))
                <div class="bg-orange-50 border border-orange-200 text-orange-800 p-4 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm mb-6 animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="flex items-start gap-3">
                        <span class="text-xl shrink-0">⚠️</span>
                        <p class="font-body text-sm leading-relaxed">
                            Perhatian: Anda belum mengunggah Kartu Identitas. Harap segera lengkapi verifikasi identitas Anda (KTP / SIM / Paspor) untuk dapat melakukan pengajuan sewa kos.
                        </p>
                    </div>
                    <button onclick="switchView('view-verifikasi', document.querySelector('a[onclick*=\'view-verifikasi\']'))" class="bg-primary text-white text-xs font-bold px-4 py-2 rounded-lg transition-all hover:bg-primary-container hover:text-on-primary-container active:scale-95 whitespace-nowrap self-end sm:self-center">
                        Verifikasi Sekarang
                    </button>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm mb-6 animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="flex items-start gap-3">
                        <span class="text-xl shrink-0">⏳</span>
                        <p class="font-body text-sm leading-relaxed">
                            Dokumen sedang ditinjau. Menunggu verifikasi dari Administrator.
                        </p>
                    </div>
                </div>
            @endif
        @endif

        {{-- 1. VIEW SETTINGS --}}
        @include('profile.partials.settings')

        {{-- 2. VIEW TRANSAKSI --}}
        @include('profile.partials.transactions')

        {{-- 3. VIEW KOS SAYA --}}
        @include('profile.partials.my_kos')

        {{-- 4. VIEW RIWAYAT PENGAJUAN --}}
        @include('profile.partials.booking_history')

        {{-- 5. VIEW RIWAYAT KOS --}}
        @include('profile.partials.kos_history')

        {{-- 6. VIEW VERIFIKASI --}}
        @include('profile.partials.verification')

        <!-- View Pesan (Obrolan) -->
        @include('profile.partials.chat')
    </section>
</main>

<x-footer />

<script>
    // Logika untuk Pindah Tab Menu
    function switchView(viewId, clickedElement) {
        // Simpan tab aktif di localStorage untuk persistence
        localStorage.setItem('active_seeker_tab', viewId);

        // Hentikan polling chat jika pindah dari tab chat
        if (viewId !== 'view-pesan') {
            if (chatPollingInterval) {
                clearInterval(chatPollingInterval);
                chatPollingInterval = null;
            }
        }

        // Sembunyikan semua section view
        const views = document.querySelectorAll('.view-section');
        views.forEach(view => {
            view.classList.add('hidden');
            view.classList.remove('block');
        });
        
        // Tampilkan section yang dituju
        const targetView = document.getElementById(viewId);
        if(targetView) {
            targetView.classList.remove('hidden');
            targetView.classList.add('block');
        }

        // Hapus status aktif dari semua menu link
        const links = document.querySelectorAll('.nav-link');
        links.forEach(link => {
            // Reset ke default class (abu-abu, hover background dll)
            link.className = "nav-link flex items-center gap-3 text-secondary hover:bg-surface-container-high rounded-lg px-4 py-3 transition-all font-body text-sm font-medium group";
            // Reset icon color
            const icon = link.querySelector('span');
            if(icon) {
                icon.className = "material-symbols-outlined text-xl group-hover:text-primary";
                icon.style.fontVariationSettings = "'FILL' 0";
            }
        });

        // Berikan status aktif pada menu yang diklik
        if(clickedElement) {
            clickedElement.className = "nav-link flex items-center gap-3 bg-primary-container text-on-primary-container rounded-lg px-4 py-3 font-semibold font-body text-sm shadow-sm group";
            const activeIcon = clickedElement.querySelector('span');
            if(activeIcon) {
                activeIcon.className = "material-symbols-outlined text-xl group-hover:text-on-primary-container";
                activeIcon.style.fontVariationSettings = "'FILL' 1";
            }
        }
    }

    // Load active tab on page load
    document.addEventListener('DOMContentLoaded', () => {
        const sessionTab = "{{ session('active_tab') }}";
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || sessionTab || localStorage.getItem('active_seeker_tab') || 'view-settings';
        const tabButton = document.querySelector(`a[onclick*="${activeTab}"]`) || document.querySelector('a[onclick*="view-settings"]');
        if (tabButton) {
            switchView(activeTab, tabButton);
        }
    });

    // Profile photo upload trigger and preview
    document.getElementById('upload_photo_btn')?.addEventListener('click', () => {
        document.getElementById('profile_photo_input').click();
    });
    
    document.getElementById('profile_photo_input')?.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile_photo_preview').src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Password Visibility Toggle Function
    function togglePasswordVisibility(button) {
        const container = button.closest('.relative');
        const input = container.querySelector('input');
        const icon = button.querySelector('.material-symbols-outlined');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    // Micro-interactions for form inputs
    document.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.classList.add('scale-[1.01]');
            input.parentElement.style.transition = 'transform 0.2s ease';
        });
        input.addEventListener('blur', () => {
            input.parentElement.classList.remove('scale-[1.01]');
        });
    });

    // Toggle handling
    const toggles = document.querySelectorAll('.switch input');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const statusSpan = this.closest('.flex').querySelector('.text-primary, .text-secondary');
            if(statusSpan) {
                statusSpan.textContent = this.checked ? 'Aktif' : 'Nonaktif';
                statusSpan.className = this.checked ? 'text-primary' : 'text-secondary';
            }
        });
    });

    // Verification Form Logic
    const identityInput = document.getElementById('identity_photo_input');
    const selfieInput = document.getElementById('selfie_photo_input');
    const agreeCheckbox = document.getElementById('agree_verification');
    const submitBtn = document.getElementById('btn_submit_verification');
    const errorMsg = document.getElementById('verification_error_msg');

    function checkVerificationForm() {
        if (!submitBtn) return;
        const hasIdentity = identityInput && identityInput.files && identityInput.files.length > 0;
        const hasSelfie = selfieInput && selfieInput.files && selfieInput.files.length > 0;
        const isAgreed = agreeCheckbox && agreeCheckbox.checked;

        if (hasIdentity && hasSelfie) {
            if (errorMsg) errorMsg.classList.add('hidden');
        } else {
            if (errorMsg) errorMsg.classList.remove('hidden');
        }

        if (hasIdentity && hasSelfie && isAgreed) {
            submitBtn.removeAttribute('disabled');
            submitBtn.className = "bg-primary text-white px-10 py-2.5 rounded-md font-medium text-sm hover:bg-primary-container transition shadow-md cursor-pointer";
        } else {
            submitBtn.setAttribute('disabled', 'true');
            submitBtn.className = "bg-[#cbd5e1] text-white px-10 py-2.5 rounded-md font-medium text-sm cursor-not-allowed transition-all";
        }
    }

    if (identityInput) {
        identityInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('identity_preview');
                    const container = document.getElementById('identity_preview_container');
                    if (preview && container) {
                        preview.src = e.target.result;
                        container.classList.remove('hidden');
                    }
                }
                reader.readAsDataURL(this.files[0]);
            }
            checkVerificationForm();
        });
    }

    if (selfieInput) {
        selfieInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('selfie_preview');
                    const container = document.getElementById('selfie_preview_container');
                    if (preview && container) {
                        preview.src = e.target.result;
                        container.classList.remove('hidden');
                    }
                }
                reader.readAsDataURL(this.files[0]);
            }
            checkVerificationForm();
        });
    }

    if (agreeCheckbox) {
        agreeCheckbox.addEventListener('change', checkVerificationForm);
    }

    // OTP Modal Logic (Phone verification)
    window.openOtpModal = function() {
        const modal = document.getElementById('otp-modal');
        if (modal) {
            // Clear inputs
            const fields = document.querySelectorAll('.otp-field');
            fields.forEach((field, index) => {
                field.value = '';
                if (index > 0) field.setAttribute('disabled', 'true');
            });
            fields[0].removeAttribute('disabled');
            fields[0].focus();
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('otp-modal-card').classList.remove('scale-95');
                document.getElementById('otp-modal-card').classList.add('scale-100');
            }, 50);
        }
    }

    window.closeOtpModal = function() {
        const modal = document.getElementById('otp-modal');
        if (modal) {
            document.getElementById('otp-modal-card').classList.remove('scale-100');
            document.getElementById('otp-modal-card').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }
    }

    // OTP inputs behavior
    document.addEventListener('DOMContentLoaded', () => {
        const otpFields = document.querySelectorAll('.otp-field');
        otpFields.forEach((field, index) => {
            field.addEventListener('input', function(e) {
                const val = this.value;
                // Clean non-digits
                this.value = val.replace(/[^0-9]/g, '');
                
                if (this.value.length === 1 && index < otpFields.length - 1) {
                    otpFields[index + 1].removeAttribute('disabled');
                    otpFields[index + 1].focus();
                }
                
                // Compile values
                compileOtp();
            });

            field.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                    otpFields[index - 1].focus();
                    otpFields[index].setAttribute('disabled', 'true');
                }
            });
        });

        // Email OTP inputs behavior
        const emailOtpFields = document.querySelectorAll('.email-otp-field');
        emailOtpFields.forEach((field, index) => {
            field.addEventListener('input', function(e) {
                const val = this.value;
                this.value = val.replace(/[^0-9]/g, '');
                
                if (this.value.length === 1 && index < emailOtpFields.length - 1) {
                    emailOtpFields[index + 1].removeAttribute('disabled');
                    emailOtpFields[index + 1].focus();
                }
                
                compileEmailOtp();
            });

            field.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                    emailOtpFields[index - 1].focus();
                    emailOtpFields[index].setAttribute('disabled', 'true');
                }
            });
        });
    });

    window.compileOtp = function() {
        let compiled = '';
        const otpFields = document.querySelectorAll('.otp-field');
        otpFields.forEach(field => {
            compiled += field.value;
        });
        document.getElementById('compiled-otp').value = compiled;
    }

    // OTP Phone Modal Logic
    window.sendPhoneOtpAndOpenModal = function() {
        const verifyBtn = document.querySelector('button[onclick="sendPhoneOtpAndOpenModal()"]');
        if (verifyBtn) {
            verifyBtn.setAttribute('disabled', 'true');
            verifyBtn.innerHTML = '<span class="material-symbols-outlined text-xs animate-spin" style="display: inline-block; animation: spin 1s linear infinite;">sync</span> Mengirim...';
        }

        // Send AJAX request
        fetch("{{ route('profile.send-phone-otp') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                openOtpModal();
            } else {
                alert('Gagal mengirim OTP: ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat mengirim kode OTP.');
        })
        .finally(() => {
            if (verifyBtn) {
                verifyBtn.removeAttribute('disabled');
                verifyBtn.innerHTML = 'Verifikasi';
            }
        });
    }

    // OTP Email Modal Logic
    window.sendEmailOtpAndOpenModal = function() {
        const emailField = document.getElementById('profile_email_field');
        const email = emailField ? emailField.value : '';
        
        // Disable button/input during request
        const verifyBtn = document.querySelector('button[onclick="sendEmailOtpAndOpenModal()"]');
        if (verifyBtn) {
            verifyBtn.setAttribute('disabled', 'true');
            verifyBtn.innerHTML = '<span class="material-symbols-outlined text-xs animate-spin" style="display: inline-block; animation: spin 1s linear infinite;">sync</span> Mengirim...';
        }

        // Send AJAX request
        fetch("{{ route('profile.send-email-otp') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                openEmailOtpModal();
            } else {
                alert('Gagal mengirim OTP: ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat mengirim kode OTP.');
        })
        .finally(() => {
            if (verifyBtn) {
                verifyBtn.removeAttribute('disabled');
                verifyBtn.innerHTML = '<span class="material-symbols-outlined text-xs">mail</span> VERIFIKASI';
            }
        });
    }

    window.openEmailOtpModal = function() {
        const modal = document.getElementById('email-otp-modal');
        if (modal) {
            // Clear inputs
            const fields = document.querySelectorAll('.email-otp-field');
            fields.forEach((field, index) => {
                field.value = '';
                if (index > 0) field.setAttribute('disabled', 'true');
            });
            fields[0].removeAttribute('disabled');
            fields[0].focus();
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('email-otp-modal-card').classList.remove('scale-95');
                document.getElementById('email-otp-modal-card').classList.add('scale-100');
            }, 50);
        }
    }

    window.closeEmailOtpModal = function() {
        const modal = document.getElementById('email-otp-modal');
        if (modal) {
            document.getElementById('email-otp-modal-card').classList.remove('scale-100');
            document.getElementById('email-otp-modal-card').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }
    }

    window.compileEmailOtp = function() {
        let compiled = '';
        const emailOtpFields = document.querySelectorAll('.email-otp-field');
        emailOtpFields.forEach(field => {
            compiled += field.value;
        });
        document.getElementById('compiled-email-otp').value = compiled;
        // REAL-TIME CHAT INTEGRATION VIA PUSHER WEBSOCKETS & LARAVEL ECHO
    const currentAuthId = {{ auth()->id() }};
    let activeConversationId = null;
    const subscribedChannels = new Set();

    // Echo configuration
    window.Pusher = Pusher;
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ config('broadcasting.connections.pusher.key') }}',
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
        forceTLS: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }
    });

    // Subscribe to all sidebar conversations on load
    function subscribeToAllConversations() {
        document.querySelectorAll('.conversation-item').forEach(item => {
            const convId = item.dataset.id;
            subscribeToConversation(convId);
        });
    }

    function subscribeToConversation(convId) {
        if (!convId || subscribedChannels.has(convId)) return;
        subscribedChannels.add(convId);
        window.Echo.private('conversation.' + convId)
            .listen('MessageSent', (e) => {
                handleIncomingMessage(e);
            });
    }

    function handleIncomingMessage(e) {
        console.log('Real-time message received:', e);
        
        const msgContainer = document.getElementById('tab-message-container');
        
        // If the message is for the currently active conversation, render it
        if (parseInt(activeConversationId) === parseInt(e.conversation_id)) {
            // Prevent duplicate bubble if we already rendered it
            const existingMsg = document.getElementById('msg-' + e.id);
            if (!existingMsg && msgContainer) {
                // Remove placeholder empty state if present
                const placeholder = msgContainer.querySelector('.py-12');
                if (placeholder) {
                    msgContainer.innerHTML = '';
                }
                
                const bubble = createMessageBubble(e);
                bubble.id = 'msg-' + e.id;
                msgContainer.appendChild(bubble);
                msgContainer.scrollTop = msgContainer.scrollHeight;
            }
        } else if (parseInt(e.sender_id) !== parseInt(currentAuthId)) {
            // Show unread badge if message is from the other participant and conversation is not active
            const sidebarItem = document.querySelector(`.conversation-item[data-id="${e.conversation_id}"]`);
            if (sidebarItem) {
                let badge = sidebarItem.querySelector('.unread-badge');
                if (badge) {
                    let count = parseInt(badge.innerText.trim()) || 0;
                    badge.innerText = count + 1;
                } else {
                    badge = document.createElement('div');
                    badge.className = 'w-4 h-4 rounded-full bg-primary text-on-primary flex items-center justify-center text-[9px] font-bold shrink-0 unread-badge';
                    badge.innerText = '1';
                    sidebarItem.appendChild(badge);
                }
            }
        }
        
        // Update sidebar item text preview and timestamp, then move it to the top
        const sidebarItem = document.querySelector(`.conversation-item[data-id="${e.conversation_id}"]`);
        if (sidebarItem) {
            const bodyText = sidebarItem.querySelector('.last-msg-body');
            const timeText = sidebarItem.querySelector('.last-msg-time');
            if (bodyText) {
                if (parseInt(e.sender_id) === parseInt(currentAuthId)) {
                    bodyText.innerHTML = `<span class="text-primary font-medium">Anda: </span>${e.body}`;
                } else {
                    bodyText.innerText = e.body;
                }
            }
            if (timeText) {
                timeText.innerText = e.time || 'Barusan';
            }
            
            const parent = document.getElementById('seeker-chat-sidebar-list');
            if (parent) {
                parent.insertBefore(sidebarItem, parent.firstChild);
            }
        }
    }

    window.loadConversation = async function(conversationId, element) {
        activeConversationId = conversationId;
        const activeConvInput = document.getElementById('active-conversation-id');
        if (activeConvInput) activeConvInput.value = conversationId;
        
        // Cari element di sidebar jika tidak di-pass
        const targetElement = element || document.querySelector(`.conversation-item[data-id="${conversationId}"]`);
        
        // Highlight sidebar item
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.classList.remove('bg-surface-container-high/60', 'border-l-4', 'border-primary');
        });
        if (targetElement) {
            targetElement.classList.add('bg-surface-container-high/60', 'border-l-4', 'border-primary');
            // Hide unread badge
            const badge = targetElement.querySelector('.unread-badge');
            if (badge) badge.remove();
        }
        
        // Update header & property banner dynamically using sidebar dataset
        if (targetElement) {
            const emptyState = document.getElementById('chat-empty-state');
            const activeArea = document.getElementById('chat-active-area');
            if (emptyState) emptyState.classList.add('hidden');
            if (activeArea) activeArea.classList.remove('hidden');

            const headerName = document.getElementById('chat-header-name');
            const headerAvatar = document.getElementById('chat-header-avatar');
            const headerRole = document.getElementById('chat-header-role');
            if (headerName) headerName.innerText = targetElement.dataset.partnerName || '';
            if (headerAvatar) headerAvatar.innerText = targetElement.dataset.partnerInitial || '';
            if (headerRole) headerRole.innerText = targetElement.dataset.partnerRole || '';

            const banner = document.getElementById('chat-property-banner');
            if (banner) {
                if (targetElement.dataset.propertyName) {
                    banner.classList.remove('hidden');
                    const propName = document.getElementById('chat-property-name');
                    const propDetails = document.getElementById('chat-property-details');
                    const propLink = document.getElementById('chat-property-link');
                    if (propName) propName.innerText = targetElement.dataset.propertyName;
                    if (propDetails) {
                        propDetails.innerText = `${targetElement.dataset.propertyArea} • Rp ${targetElement.dataset.propertyPrice}/bln`;
                    }
                    if (propLink) propLink.href = `/kos/${targetElement.dataset.propertySlug}`;

                    const img = document.getElementById('chat-property-image');
                    const placeholder = document.getElementById('chat-property-placeholder');
                    if (img && placeholder) {
                        if (targetElement.dataset.propertyImage) {
                            img.src = targetElement.dataset.propertyImage;
                            img.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                        } else {
                            img.classList.add('hidden');
                            placeholder.classList.remove('hidden');
                        }
                    }
                } else {
                    banner.classList.add('hidden');
                }
            }
        }
        
        try {
            const response = await fetch(`/chat/${conversationId}`, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            if (!response.ok) throw new Error("Failed to load chat");
            
            const data = await response.json();
            console.log('Chat Data:', data);

            // Render Messages
            const msgContainer = document.getElementById('tab-message-container');
            if (msgContainer) {
                msgContainer.innerHTML = '';
                
                if (data.messages.length === 0) {
                    msgContainer.innerHTML = `
                        <div class="flex items-center justify-center h-full py-12 text-secondary flex-col">
                            <span class="material-symbols-outlined text-3xl mb-1 text-outline-variant">forum</span>
                            <p class="text-xs font-bold">Mulai obrolan Anda</p>
                        </div>
                    `;
                } else {
                    data.messages.forEach(msg => {
                        const bubble = createMessageBubble(msg);
                        bubble.id = 'msg-' + msg.id;
                        msgContainer.appendChild(bubble);
                    });
                }
                
                msgContainer.scrollTop = msgContainer.scrollHeight;
            }

            // Ensure subscribed to this conversation channel
            subscribeToConversation(conversationId);
            
        } catch (err) {
            console.error("Error loading chat:", err);
        }
    }

    function createMessageBubble(msg) {
        const isSelf = msg.is_self !== undefined ? msg.is_self : (parseInt(msg.sender_id) === parseInt(currentAuthId));
        const outerDiv = document.createElement('div');
        outerDiv.className = `flex ${isSelf ? 'justify-end' : 'justify-start'}`;
        
        const innerDiv = document.createElement('div');
        innerDiv.className = `flex flex-col max-w-[75%] md:max-w-[65%] gap-1`;
        
        const bubble = document.createElement('div');
        bubble.className = `p-3.5 rounded-2xl shadow-sm leading-relaxed text-sm ${isSelf ? 'bg-primary text-on-primary rounded-tr-none' : 'bg-surface-container-lowest text-on-surface rounded-tl-none border border-outline-variant/20'}`;
        
        const p = document.createElement('p');
        p.className = 'whitespace-pre-wrap break-words';
        p.innerText = msg.body;
        bubble.appendChild(p);
        
        const meta = document.createElement('div');
        meta.className = `flex items-center gap-1 text-[9px] text-secondary mt-0.5 ${isSelf ? 'justify-end' : 'justify-start'}`;
        
        const timeSpan = document.createElement('span');
        let timeStr = msg.time;
        if (!timeStr && msg.created_at) {
            const date = new Date(msg.created_at);
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            timeStr = `${hours}:${minutes}`;
        }
        timeSpan.innerText = timeStr || '';
        meta.appendChild(timeSpan);
        
        if (isSelf) {
            const checkIcon = document.createElement('span');
            checkIcon.className = 'material-symbols-outlined text-xs text-primary font-bold';
            checkIcon.innerText = msg.is_read ? 'done_all' : 'done';
            meta.appendChild(checkIcon);
        }
        
        innerDiv.appendChild(bubble);
        innerDiv.appendChild(meta);
        outerDiv.appendChild(innerDiv);
        return outerDiv;
    }

    window.sendMessage = async function(event) {
        event.preventDefault();
        const input = document.getElementById('chat-msg-input');
        const text = input.value.trim();
        if (!text || !activeConversationId) return;
        
        try {
            const response = await fetch(`/chat/${activeConversationId}/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ body: text })
            });
            
            if (!response.ok) throw new Error("Failed to send message");
            
            const data = await response.json();
            if (data.success) {
                input.value = '';
                input.style.height = 'auto';
                
                const msgContainer = document.getElementById('tab-message-container');
                if (msgContainer.querySelector('.py-12')) {
                    msgContainer.innerHTML = '';
                }
                
                const bubble = createMessageBubble(data.message);
                bubble.id = 'msg-' + data.message.id;
                msgContainer.appendChild(bubble);
                msgContainer.scrollTop = msgContainer.scrollHeight;
                
                // Update last message in sidebar list
                const sidebarItem = document.querySelector(`.conversation-item[data-id="${activeConversationId}"]`);
                if (sidebarItem) {
                    const bodyText = sidebarItem.querySelector('.last-msg-body');
                    const timeText = sidebarItem.querySelector('.last-msg-time');
                    if (bodyText) bodyText.innerHTML = `<span class="text-primary font-medium">Anda: </span>${text}`;
                    if (timeText) timeText.innerText = 'Barusan';
                    
                    const parent = document.getElementById('seeker-chat-sidebar-list');
                    if (parent) {
                        parent.insertBefore(sidebarItem, parent.firstChild);
                    }
                }
            }
        } catch (err) {
            console.error("Error sending message:", err);
        }
    }

    // Auto-open chat if conversation_id query parameter exists
    document.addEventListener('DOMContentLoaded', () => {
        // Subscribe to initial channels
        subscribeToAllConversations();
        
        // Scroll fallback messages to bottom
        const msgContainer = document.getElementById('tab-message-container');
        if (msgContainer) {
            msgContainer.scrollTop = msgContainer.scrollHeight;
        }
        
        const urlParams = new URLSearchParams(window.location.search);
        const conversationId = urlParams.get('conversation_id');
        if (conversationId) {
            const sidebarItem = document.querySelector(`.conversation-item[data-id="${conversationId}"]`);
            if (sidebarItem) {
                setTimeout(() => {
                    loadConversation(conversationId, sidebarItem);
                }, 100);
            }
        }
    });
</script>

<!-- CSS Animation Keyframe for spin -->
<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<!-- MODAL VERIFIKASI OTP EMAIL -->
<div id="email-otp-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEmailOtpModal()"></div>
    
    <!-- Content Card -->
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-8 border border-outline-variant/30 z-10 scale-95 transition-all duration-300" id="email-otp-modal-card">
        <button onclick="closeEmailOtpModal()" class="absolute right-4 top-4 text-secondary hover:text-primary transition-colors" type="button">
            <span class="material-symbols-outlined">close</span>
        </button>
        
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-primary text-2xl">mail</span>
            </div>
            <h3 class="font-headline text-2xl font-bold text-on-surface">Verifikasi Email Anda</h3>
            <p class="font-body text-sm text-secondary mt-2">Masukkan 4 digit kode OTP yang kami kirimkan ke email Anda.</p>

        </div>
        
        <form action="{{ route('profile.verify-email') }}" method="POST" class="space-y-6">
            @csrf
            <div class="flex justify-center gap-3" id="email-otp-inputs">
                <input type="text" maxlength="1" class="email-otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required>
                <input type="text" maxlength="1" class="email-otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required disabled>
                <input type="text" maxlength="1" class="email-otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required disabled>
                <input type="text" maxlength="1" class="email-otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required disabled>
            </div>
            
            <input type="hidden" name="otp" id="compiled-email-otp">
            
            <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-bold text-sm tracking-wider uppercase hover:bg-primary-container transition-all active:scale-[0.98]">
                Konfirmasi Kode
            </button>
        </form>
    </div>
</div>

<!-- MODAL VERIFIKASI OTP TELEPON -->
<div id="otp-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeOtpModal()"></div>
    
    <!-- Content Card -->
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-8 border border-outline-variant/30 z-10 scale-95 transition-all duration-300" id="otp-modal-card">
        <button onclick="closeOtpModal()" class="absolute right-4 top-4 text-secondary hover:text-primary transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
        
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-primary text-2xl">sms</span>
            </div>
            <h3 class="font-headline text-2xl font-bold text-on-surface">Verifikasi Nomor Handphone</h3>
            <p class="font-body text-sm text-secondary mt-2">Masukkan 4 digit kode OTP yang kami kirimkan ke nomor WhatsApp Anda.</p>
            

        </div>
        
        <form action="{{ route('profile.verify-phone') }}" method="POST" class="space-y-6">
            @csrf
            <div class="flex justify-center gap-3" id="otp-inputs">
                <input type="text" maxlength="1" class="otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required>
                <input type="text" maxlength="1" class="otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required disabled>
                <input type="text" maxlength="1" class="otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required disabled>
                <input type="text" maxlength="1" class="otp-field w-12 h-12 text-center text-xl font-bold bg-surface border border-outline-variant rounded-lg focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all" required disabled>
            </div>
            
            <input type="hidden" name="otp" id="compiled-otp">
            
            <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-bold text-sm tracking-wider uppercase hover:bg-primary-container transition-all active:scale-[0.98]">
                Konfirmasi Kode
            </button>
        </form>
    </div>
</div>

</body>
</html>