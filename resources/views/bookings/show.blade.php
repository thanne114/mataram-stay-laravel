<!DOCTYPE html><html lang="id"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Detail Pemesanan - Mataram Stay</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700;800&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<script>
    tailwind.config = {
        theme: { extend: {
            "colors": {
                "outline-variant": "#d8d0c8", "background": "#faf5ee", "surface": "#faf5ee",
                "on-surface": "#3a302a", "primary": "#c2652a", "on-primary": "#ffffff",
                "primary-container": "#e08850", "secondary": "#78706a",
                "surface-container-lowest": "#ffffff", "surface-container-low": "#f6f0e8",
                "on-surface-variant": "#605850", "primary-fixed": "#fbe8d8",
                "outline": "#9a9088", "error": "#c0392b", "on-primary-fixed": "#401a08",
                "tertiary": "#8c3c3c", "tertiary-fixed": "#fce0e0",
                "on-tertiary-fixed-variant": "#6e3030"
            },
            "fontFamily": {
                "headline": ["EB Garamond", "serif"],
                "body": ["Manrope", "sans-serif"],
                "label": ["Manrope", "sans-serif"]
            }
        }}
    }
</script>
<style>.font-headline { font-family: 'EB Garamond', serif; }</style>
</head>
<body class="bg-background text-on-surface font-body antialiased min-h-screen flex flex-col">
<x-navbar />

<main class="flex-grow max-w-3xl mx-auto w-full px-4 md:px-8 py-10">
    <h1 class="font-headline text-3xl font-bold mb-2">Detail Pemesanan</h1>
    <p class="text-secondary text-sm mb-8">ID Booking: #{{ $booking->id }}</p>

    @if(session('success'))
    <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 flex items-center gap-3 mb-6">
        <span class="material-symbols-outlined">check_circle</span>
        <p class="font-bold text-sm">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3 mb-6">
        <span class="material-symbols-outlined">error</span>
        <p class="font-bold text-sm">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Alert Dibatalkan / Kedaluwarsa --}}
    @if($booking->status === 'Cancelled')
    <div class="p-6 rounded-xl bg-red-50/50 border border-red-200 text-on-surface mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="space-y-1">
            <h3 class="font-headline text-lg font-bold text-red-700 flex items-center gap-2">
                <span class="material-symbols-outlined">cancel</span>
                <span>Pemesanan Dibatalkan / Kedaluwarsa</span>
            </h3>
            <p class="text-xs text-secondary leading-relaxed font-light">Pemesanan ini telah dibatalkan atau waktu pembayaran telah habis. Anda dapat memesan ulang jika kamar masih tersedia.</p>
        </div>
        @if(auth()->id() === $booking->user_id)
            <a href="{{ route('booking.create', ['room_type_id' => $booking->room_type_id]) }}" class="bg-primary text-on-primary px-6 py-3 rounded-xl font-label font-bold text-xs hover:bg-primary-container transition-all shadow-sm shrink-0 text-center flex items-center gap-1.5 active:scale-[0.98]">
                <span class="material-symbols-outlined text-sm">replay</span>
                Pesan Ulang Kamar
            </a>
        @endif
    </div>
    @endif

    {{-- Status Badge --}}
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <p class="text-xs text-secondary uppercase tracking-wider mb-1">Status Booking</p>
                @php
                    $statusColors = [
                        'Pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                        'Active' => 'bg-green-50 text-green-700 border-green-200',
                        'Completed' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'Cancelled' => 'bg-red-50 text-red-700 border-red-200',
                        'Verified' => 'bg-green-50 text-green-700 border-green-200',
                    ];
                    $paymentColors = [
                        'Unpaid' => 'bg-red-50 text-red-700 border-red-200',
                        'Checking' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                        'Paid' => 'bg-green-50 text-green-700 border-green-200',
                    ];
                @endphp
                <div class="flex gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusColors[$booking->status] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                        {{ $booking->status }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $paymentColors[$booking->payment_status] ?? '' }}">
                        Pembayaran: {{ $booking->payment_status }}
                    </span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs text-secondary">Total</p>
                <p class="font-headline text-2xl font-bold text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Owner Approval Actions --}}
    @if(auth()->id() === $property->user_id && $booking->status === 'Pending' && !$booking->is_approved)
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-primary/30 mb-6 bg-gradient-to-br from-white to-[#faf5ee]">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary text-2xl">gavel</span>
            <h3 class="font-headline text-lg font-bold">Persetujuan Sewa (Request to Book)</h3>
        </div>
        <p class="text-sm text-secondary mb-6">Pencari kos telah mengajukan permohonan sewa untuk kamar ini. Harap setujui atau tolak pengajuan sewa.</p>
        <div class="flex flex-col sm:flex-row gap-3">
            <form action="{{ route('booking.approve', $booking) }}" method="POST" class="flex-grow">
                @csrf
                <button type="submit" class="w-full bg-green-600 text-white py-3.5 rounded-xl font-label font-bold text-sm hover:bg-green-700 transition-all flex items-center justify-center gap-2 shadow-md active:scale-[0.98]">
                    <span class="material-symbols-outlined text-sm">check_circle</span>
                    Terima Pesanan
                </button>
            </form>
            <form action="{{ route('booking.reject', $booking) }}" method="POST" class="flex-grow" onsubmit="return confirm('Apakah Anda yakin ingin menolak pesanan ini?')">
                @csrf
                <button type="submit" class="w-full bg-red-600 text-white py-3.5 rounded-xl font-label font-bold text-sm hover:bg-red-700 transition-all flex items-center justify-center gap-2 shadow-md active:scale-[0.98]">
                    <span class="material-symbols-outlined text-sm">cancel</span>
                    Tolak Pesanan
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Seeker Warning Banner: Waiting for Owner Approval --}}
    @if(auth()->id() === $booking->user_id && !$booking->is_approved && $booking->status === 'Pending')
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-yellow-300 mb-6 bg-gradient-to-br from-white to-[#fffbeb]">
        <div class="flex items-center gap-3 mb-3">
            <span class="material-symbols-outlined text-amber-600 text-2xl animate-pulse">hourglass_empty</span>
            <h3 class="font-headline text-lg font-bold text-amber-800">Menunggu Persetujuan Pemilik Kos</h3>
        </div>
        <p class="text-sm text-secondary mb-5">Anda akan dapat melakukan pembayaran setelah pemilik menerima pengajuan sewa ini.</p>
        <form action="{{ route('booking.cancel', $booking) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?')">
            @csrf
            <button type="submit" class="w-full sm:w-auto bg-white text-tertiary border border-tertiary px-6 py-3 rounded-xl font-label font-bold text-xs hover:bg-red-50 transition-all text-center flex items-center justify-center gap-1 active:scale-[0.98]">
                <span class="material-symbols-outlined text-sm">cancel</span>
                Batalkan Pengajuan
            </button>
        </form>
    </div>
    @endif

    {{-- Info Kos --}}
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 mb-6">
        <h3 class="font-headline text-lg font-bold mb-4">Informasi Kos</h3>
        <div class="flex gap-4">
            @if($property->main_image)
            <div class="w-24 h-24 rounded-lg overflow-hidden shrink-0">
                <img src="{{ asset('storage/' . $property->main_image) }}" alt="{{ $property->name }}" class="w-full h-full object-cover">
            </div>
            @endif
            <div>
                <a href="{{ route('property.show', $property->slug) }}" class="font-headline text-xl font-bold hover:text-primary transition-colors">{{ $property->name }}</a>
                <p class="text-sm text-secondary">{{ $booking->roomType->name }} • {{ $property->type }} • {{ $property->area }}</p>
                <p class="text-xs text-secondary mt-1">{{ $property->address }}</p>
            </div>
        </div>
    </div>

    {{-- Detail Booking --}}
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 mb-6">
        <h3 class="font-headline text-lg font-bold mb-4">Detail Pemesanan</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-secondary uppercase tracking-wider">Pemesan</p>
                <p class="font-bold text-sm">{{ $booking->user->name }}</p>
            </div>
            <div>
                <p class="text-xs text-secondary uppercase tracking-wider">Check-in</p>
                <p class="font-bold text-sm">{{ $booking->check_in_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-secondary uppercase tracking-wider">Durasi</p>
                <p class="font-bold text-sm">{{ $booking->duration_months }} Bulan</p>
            </div>
            <div>
                <p class="text-xs text-secondary uppercase tracking-wider">Check-out</p>
                <p class="font-bold text-sm">{{ $booking->check_out_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-secondary uppercase tracking-wider">Harga / Bulan</p>
                <p class="font-bold text-sm">Rp {{ number_format($booking->roomType->price_per_month, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-xs text-secondary uppercase tracking-wider">Dibuat</p>
                <p class="font-bold text-sm">{{ $booking->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <div class="pt-5 mt-5 border-t border-outline-variant/30">
            <h4 class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-3">Rincian Pembayaran</h4>
            <div class="space-y-2">
                <div class="flex justify-between items-center text-sm text-secondary">
                    <span>Sewa Kamar pokok ({{ $booking->duration_months }} Bulan)</span>
                    <span class="font-medium text-on-surface">Rp {{ number_format($booking->room_subtotal > 0 ? $booking->room_subtotal : $booking->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm text-secondary">
                    <span>Biaya Layanan Admin</span>
                    <span class="font-medium text-on-surface">Rp {{ number_format($booking->admin_fee, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-outline-variant/20">
                    <span class="font-bold text-on-surface text-sm">Total Pembayaran</span>
                    <span class="font-bold text-primary text-base">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Pembayaran Midtrans (Otomatis) --}}
    @if(auth()->id() === $booking->user_id && $booking->is_approved && in_array($booking->payment_status, ['Unpaid', 'Checking']) && $booking->status === 'Pending')
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-primary/30 mb-6 bg-gradient-to-br from-white to-[#faf5ee]">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">credit_card</span>
            <h3 class="font-headline text-lg font-bold">Pembayaran Instan & Otomatis</h3>
        </div>
        <p class="text-sm text-secondary mb-6">Bayar secara instan menggunakan E-Wallet (GoPay, ShopeePay), QRIS, Virtual Account Bank (BCA, Mandiri, BNI, BRI), atau gerai retail (Alfamart, Indomaret).</p>
        
        <div class="flex flex-col sm:flex-row gap-3">
            @if($booking->payment_token)
                <button id="pay-button" class="flex-grow bg-primary text-on-primary py-3.5 rounded-xl font-label font-bold text-sm hover:bg-primary-container transition-all flex items-center justify-center gap-2 shadow-md active:scale-[0.98]">
                    <span class="material-symbols-outlined">payments</span>
                    Bayar Sekarang
                </button>
            @else
                <div class="flex-grow p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined">error</span>
                    Gagal memuat sistem pembayaran otomatis. Silakan gunakan Transfer Manual di bawah.
                </div>
            @endif
            
            <form action="{{ route('booking.cancel', $booking) }}" method="POST" class="shrink-0 w-full sm:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?')">
                @csrf
                <button type="submit" class="w-full sm:w-auto bg-white text-tertiary border border-tertiary px-6 py-3.5 rounded-xl font-label font-bold text-sm hover:bg-red-50 transition-all text-center flex items-center justify-center gap-1 active:scale-[0.98]">
                    <span class="material-symbols-outlined text-lg">cancel</span>
                    Batalkan Pesanan
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Bukti Pembayaran (Transfer Manual) --}}
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 mb-6">
        <h3 class="font-headline text-lg font-bold mb-2">
            @if(auth()->id() === $booking->user_id && $booking->is_approved && $booking->payment_status === 'Unpaid' && $booking->status === 'Pending')
                Transfer Manual (Alternatif)
            @else
                Bukti Pembayaran / Transfer Manual
            @endif
        </h3>
        
        @if(auth()->id() === $booking->user_id && $booking->is_approved && $booking->payment_status === 'Unpaid' && $booking->status === 'Pending')
            <p class="text-xs text-secondary mb-4">Punya kendala dengan pembayaran otomatis? Anda dapat mentransfer langsung ke Pemilik Kos dan mengunggah buktinya di sini.</p>
        @endif
        
        @if($booking->payment_proof)
            <div class="rounded-lg overflow-hidden border border-outline-variant/30 mb-4">
                <img src="{{ asset('storage/' . $booking->payment_proof) }}" alt="Bukti Pembayaran" class="w-full max-h-96 object-contain bg-surface-container-high">
            </div>
        @endif

        {{-- Seeker: Upload bukti pembayaran --}}
        @if(auth()->id() === $booking->user_id && $booking->is_approved && in_array($booking->payment_status, ['Unpaid', 'Checking']))
        <form action="{{ route('booking.upload-proof', $booking) }}" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf
            <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider block mb-2">
                {{ $booking->payment_proof ? 'Upload Ulang Bukti Pembayaran' : 'Upload Bukti Pembayaran' }}
            </label>
            <div class="flex gap-3">
                <input type="file" name="payment_proof" accept="image/*" required 
                    class="flex-1 bg-surface-bright border border-outline-variant rounded-lg px-4 py-2 text-sm">
                <button type="submit" class="bg-primary text-on-primary px-6 py-2 rounded-lg font-label font-bold text-sm hover:bg-primary-container transition-all">
                    Upload
                </button>
            </div>
            @error('payment_proof')
            <p class="text-error text-xs mt-1">{{ $message }}</p>
            @enderror
        </form>
        @endif

        {{-- Owner: Verifikasi pembayaran --}}
        @if(auth()->id() === $property->user_id && $booking->payment_status === 'Checking')
        <form action="{{ route('booking.verify', $booking) }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-label font-bold hover:bg-green-700 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">verified</span>
                Verifikasi Pembayaran
            </button>
        </form>
        @endif
    </div>

    {{-- Owner: Update Status --}}
    @if(auth()->id() === $property->user_id && $booking->status === 'Active')
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 mb-6">
        <h3 class="font-headline text-lg font-bold mb-4">Kelola Status</h3>
        <div class="flex gap-3">
            <form action="{{ route('booking.update-status', $booking) }}" method="POST" class="flex-1">
                @csrf
                <input type="hidden" name="status" value="Completed">
                <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-bold text-sm hover:bg-blue-700 transition-all">
                    Tandai Selesai
                </button>
            </form>
            <form action="{{ route('booking.update-status', $booking) }}" method="POST" class="flex-1">
                @csrf
                <input type="hidden" name="status" value="Cancelled">
                <button type="submit" class="w-full bg-red-600 text-white py-2.5 rounded-lg font-bold text-sm hover:bg-red-700 transition-all" onclick="return confirm('Yakin ingin membatalkan booking ini?')">
                    Batalkan
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Admin: Refund Dispute --}}
    @if(auth()->user()->isAdmin() && $booking->status === 'Cancelled' && $booking->payment_status === 'Paid' && $booking->escrow_status !== 'refunded')
    <div class="bg-red-50 rounded-xl p-6 border border-red-200 mb-6">
        <h3 class="font-headline text-lg font-bold text-red-800 mb-2">Pusat Dispute Admin: Refund Dana</h3>
        <p class="text-xs text-red-700 font-body mb-4">Pesanan ini telah dibatalkan tetapi berstatus lunas. Sebagai administrator, Anda dapat menandai pesanan ini telah di-refund.</p>
        <form action="{{ route('admin.booking.refund', $booking) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memproses refund dana untuk pesanan ini?')">
            @csrf
            <button type="submit" class="w-full bg-red-600 text-white py-2.5 rounded-lg font-bold text-sm hover:bg-red-700 transition-all">
                Proses Pengembalian Dana (Refund)
            </button>
        </form>
    </div>
    @endif

    {{-- Review (Seeker, setelah Completed) --}}
    @if(auth()->id() === $booking->user_id && $booking->status === 'Completed' && !$booking->review)
    <div id="review-section" class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30">
        <h3 class="font-headline text-lg font-bold mb-4">Tulis Ulasan</h3>
        <form action="{{ route('review.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider block mb-2">Rating</label>
                <div class="flex gap-1" id="star-rating-container">
                    @for($i = 1; $i <= 5; $i++)
                    <label class="cursor-pointer">
                        <input type="radio" name="rating" value="{{ $i }}" class="hidden rating-star-input" {{ $i === 5 ? 'checked' : '' }}>
                        <span class="material-symbols-outlined text-3xl text-outline-variant star-icon transition-colors" data-value="{{ $i }}" style="font-variation-settings: 'FILL' 1;">star</span>
                    </label>
                    @endfor
                </div>
            </div>
            <div>
                <label class="text-xs font-bold text-on-surface-variant uppercase tracking-wider block mb-1.5">Komentar (Opsional)</label>
                <textarea name="comment" rows="3" placeholder="Ceritakan pengalaman Anda tinggal di kos ini..." 
                    class="w-full bg-surface-bright border border-outline-variant rounded-lg px-4 py-3 text-sm focus:border-primary focus:ring-1 focus:ring-primary"></textarea>
            </div>
            <button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded-lg font-label font-bold hover:bg-primary-container transition-all">
                Kirim Ulasan
            </button>
        </form>
    </div>
    @endif

    {{-- Review sudah ditulis --}}
    @if($booking->review)
    <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30">
        <h3 class="font-headline text-lg font-bold mb-4">Ulasan Anda</h3>
        <div class="flex items-center gap-1 mb-2">
            @for($i = 1; $i <= 5; $i++)
                <span class="material-symbols-outlined text-sm {{ $i <= $booking->review->rating ? 'text-primary' : 'text-outline-variant' }}" style="font-variation-settings: 'FILL' 1;">star</span>
            @endfor
        </div>
        @if($booking->review->comment)
        <p class="text-sm text-on-surface-variant">{{ $booking->review->comment }}</p>
        @endif
        <p class="text-xs text-secondary mt-2">{{ $booking->review->created_at->diffForHumans() }}</p>
    </div>
    @endif
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star-icon');
        const inputs = document.querySelectorAll('.rating-star-input');
        
        function updateStars(checkedValue) {
            stars.forEach(star => {
                const val = parseInt(star.getAttribute('data-value'));
                if (val <= checkedValue) {
                    star.classList.remove('text-outline-variant');
                    star.classList.add('text-primary');
                } else {
                    star.classList.remove('text-primary');
                    star.classList.add('text-outline-variant');
                }
            });
        }

        // Initialize rating display
        const checkedInput = document.querySelector('.rating-star-input:checked');
        if (checkedInput) {
            updateStars(parseInt(checkedInput.value));
        }

        inputs.forEach(input => {
            input.addEventListener('change', function() {
                updateStars(parseInt(this.value));
            });
        });

        // Hover effect for dynamic feedback
        stars.forEach(star => {
            star.addEventListener('mouseenter', function() {
                const val = parseInt(this.getAttribute('data-value'));
                stars.forEach(s => {
                    const v = parseInt(s.getAttribute('data-value'));
                    if (v <= val) {
                        s.classList.remove('text-outline-variant');
                        s.classList.add('text-primary');
                    } else {
                        s.classList.remove('text-primary');
                        s.classList.add('text-outline-variant');
                    }
                });
            });
            
            star.addEventListener('mouseleave', function() {
                const checkedValInput = document.querySelector('.rating-star-input:checked');
                const activeVal = checkedValInput ? parseInt(checkedValInput.value) : 5;
                updateStars(activeVal);
            });
        });
    });
</script>

@if(auth()->id() === $booking->user_id && in_array($booking->payment_status, ['Unpaid', 'Checking']) && $booking->status === 'Pending' && $booking->payment_token)
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $booking->payment_token }}', {
                onSuccess: function(result){
                    window.location.reload();
                },
                onPending: function(result){
                    window.location.reload();
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                }
            });
        };
    </script>
@endif
<x-footer />
</body></html>
