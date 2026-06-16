<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Pemesanan - Mataram Stay</title>
</head>
<body style="margin: 0; padding: 0; background-color: #faf5ee; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #3a302a; -webkit-font-smoothing: antialiased;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #faf5ee; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border: 1px solid #d8d0c8; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(58, 48, 42, 0.03);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #ffffff; padding: 32px 40px 20px 40px; border-bottom: 1px solid #ece6dc;">
                            <h1 style="margin: 0; font-family: Georgia, serif; font-size: 28px; font-weight: bold; color: #c2652a; font-style: italic;">Mataram Stay</h1>
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #78706a; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 600;">Notifikasi Transaksi</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 40px 32px 40px;">
                            
                            @if($type === 'created_seeker')
                                <!-- Seeker: Booking Created -->
                                <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>{{ $booking->user->name }}</strong>,</p>
                                <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #3a302a;">Pemesanan kos Anda berhasil dibuat! Harap selesaikan pembayaran sebelum batas waktu agar kamar pilihan Anda tidak dilepaskan.</p>
                                
                            @elseif($type === 'created_owner')
                                <!-- Owner: Booking Created -->
                                <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>{{ $booking->roomType->property->owner->name }}</strong>,</p>
                                <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #3a302a;">Ada pengajuan sewa kos baru untuk properti Anda. Saat ini pemesanan menunggu pembayaran oleh calon penyewa.</p>
                                
                            @elseif($type === 'proof_uploaded')
                                <!-- Owner: Seeker Uploaded Proof -->
                                <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>{{ $booking->roomType->property->owner->name }}</strong>,</p>
                                <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #3a302a;">Penyewa <strong>{{ $booking->user->name }}</strong> telah mengunggah bukti pembayaran manual. Silakan verifikasi kecocokan transfer dana di mutasi rekening Anda.</p>
                                
                            @elseif($type === 'payment_success_seeker')
                                <!-- Seeker: Payment Success -->
                                <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>{{ $booking->user->name }}</strong>,</p>
                                <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #3a302a;">Pembayaran Anda berhasil diverifikasi! Pemesanan sewa kos Anda sekarang berstatus **Aktif**. Selamat datang di hunian baru Anda!</p>
                                
                            @elseif($type === 'payment_success_owner')
                                <!-- Owner: Payment Success -->
                                <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>{{ $booking->roomType->property->owner->name }}</strong>,</p>
                                <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #3a302a;">Pembayaran sewa oleh <strong>{{ $booking->user->name }}</strong> telah resmi diterima. Kamar kos Anda berhasil disewakan dan ketersediaan kamar telah otomatis diperbarui.</p>
                                
                            @elseif($type === 'cancelled_seeker')
                                <!-- Seeker: Booking Cancelled -->
                                <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>{{ $booking->user->name }}</strong>,</p>
                                <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #3a302a;">Pemesanan kos Anda untuk unit berikut telah dibatalkan karena batas waktu pembayaran telah kedaluwarsa atau pesanan dibatalkan.</p>
                                
                            @elseif($type === 'overbooked_seeker')
                                <!-- Seeker: Overbooked -->
                                <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>{{ $booking->user->name }}</strong>,</p>
                                <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #c0392b;">Kami memohon maaf yang sebesar-besarnya. Pembayaran Anda telah kami terima, namun karena persaingan pemesanan yang sangat cepat, kamar kos ini sudah terisi penuh sesaat sebelum pembayaran Anda terverifikasi.</p>
                                <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; color: #3a302a;">Tim administrasi kami akan segera menghubungi Anda dalam waktu 24 jam untuk membantu proses pengembalian dana (*refund*) penuh atau menawarkan opsi kos alternatif yang serupa.</p>

                            @elseif($type === 'overbooked_admin')
                                <!-- Admin: Overbooked Warning -->
                                <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>Administrator</strong>,</p>
                                <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #c0392b;">**PERINGATAN DARURAT:** Terjadi kondisi transaksi ganda (*overbooked*) pada Booking ID: <strong>#{{ $booking->id }}</strong>. Pembayaran Midtrans telah lunas (Paid) namun stok ketersediaan tipe kamar ini sudah kosong (0).</p>
                            @endif

                            <!-- Rincian Properti Card -->
                            <div style="background-color: #faf5ee; border: 1px solid #ece6dc; border-radius: 12px; padding: 20px; margin-bottom: 30px;">
                                <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #c2652a; font-family: Georgia, serif;">Informasi Properti & Kamar</h3>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px; line-height: 1.6;">
                                    <tr>
                                        <td width="40%" style="color: #78706a; padding-bottom: 6px;">Kos:</td>
                                        <td style="font-weight: 600; padding-bottom: 6px; color: #3a302a;">{{ $booking->roomType->property->name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #78706a; padding-bottom: 6px;">Tipe Kamar:</td>
                                        <td style="font-weight: 600; padding-bottom: 6px; color: #3a302a;">{{ $booking->roomType->name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #78706a; padding-bottom: 6px;">Durasi Sewa:</td>
                                        <td style="padding-bottom: 6px; color: #3a302a;">{{ $booking->duration_months }} Bulan</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #78706a; padding-bottom: 6px;">Rencana Masuk:</td>
                                        <td style="padding-bottom: 6px; color: #3a302a;">{{ $booking->check_in_date->format('d M Y') }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Rincian Biaya Card (Untuk event yang melibatkan transaksi keuangan) -->
                            @if(in_array($type, ['created_seeker', 'payment_success_seeker', 'payment_success_owner', 'overbooked_seeker', 'overbooked_admin']))
                            <div style="background-color: #ffffff; border: 1px dashed #c2652a; border-radius: 12px; padding: 20px; margin-bottom: 30px;">
                                <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #c2652a; font-family: Georgia, serif;">Rincian Pembayaran</h3>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px; line-height: 1.6;">
                                    <tr>
                                        <td width="40%" style="color: #78706a; padding-bottom: 6px;">Subtotal Sewa:</td>
                                        <td style="padding-bottom: 6px; text-align: right; color: #3a302a;">Rp {{ number_format($booking->room_subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #78706a; padding-bottom: 6px;">Biaya Admin:</td>
                                        <td style="padding-bottom: 6px; text-align: right; color: #3a302a;">Rp {{ number_format($booking->admin_fee, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #78706a; padding-top: 6px; border-top: 1px solid #ece6dc; font-weight: bold;">Total Biaya:</td>
                                        <td style="padding-top: 6px; border-top: 1px solid #ece6dc; font-weight: bold; color: #c2652a; text-align: right; font-size: 16px;">
                                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @endif

                            <!-- Kontak Penting (Khusus Sukses Bayar) -->
                            @if($type === 'payment_success_seeker')
                            <div style="background-color: #f6f0e8; border-left: 4px solid #c2652a; border-radius: 4px; padding: 15px; margin-bottom: 30px;">
                                <h4 style="margin: 0 0 8px 0; font-size: 14px; color: #3a302a; font-family: Georgia, serif;">Kontak Pemilik Kos:</h4>
                                <p style="margin: 0; font-size: 13px; color: #3a302a;">
                                    Nama: <strong>{{ $booking->roomType->property->owner->name }}</strong><br>
                                    WhatsApp: <strong>{{ $booking->roomType->property->owner->no_whatsapp ?? '-' }}</strong><br>
                                    Email: <strong>{{ $booking->roomType->property->owner->email }}</strong>
                                </p>
                            </div>
                            @elseif($type === 'payment_success_owner' || $type === 'proof_uploaded' || $type === 'overbooked_admin')
                            <div style="background-color: #f6f0e8; border-left: 4px solid #c2652a; border-radius: 4px; padding: 15px; margin-bottom: 30px;">
                                <h4 style="margin: 0 0 8px 0; font-size: 14px; color: #3a302a; font-family: Georgia, serif;">Kontak Penyewa (Seeker):</h4>
                                <p style="margin: 0; font-size: 13px; color: #3a302a;">
                                    Nama: <strong>{{ $booking->user->name }}</strong><br>
                                    WhatsApp: <strong>{{ $booking->user->no_whatsapp ?? '-' }}</strong><br>
                                    Email: <strong>{{ $booking->user->email }}</strong>
                                </p>
                            </div>
                            @endif

                            <!-- Call to Action Button -->
                            @if(in_array($type, ['created_seeker', 'created_owner', 'proof_uploaded', 'payment_success_seeker', 'payment_success_owner']))
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 32px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('booking.show', $booking->id) }}" style="background-color: #c2652a; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block; box-shadow: 0 4px 10px rgba(194, 101, 42, 0.2);">Pantau Status Pemesanan</a>
                                    </td>
                                </tr>
                            </table>
                            @endif
                            
                            <hr style="border: 0; border-top: 1px solid #ece6dc; margin: 32px 0 24px 0;">
                            
                            <p style="margin: 0; font-size: 14px; color: #78706a;">Salam hangat,<br><strong style="color: #c2652a;">Tim Mataram Stay</strong></p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #faf5ee; padding: 24px 40px; border-top: 1px solid #ece6dc;">
                            <p style="margin: 0; font-size: 11px; line-height: 1.5; color: #78706a; text-align: center;">
                                &copy; 2026 Mataram Stay. All rights reserved.<br>
                                Jl. Raya Lombok, Mataram, Nusa Tenggara Barat, Indonesia.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
