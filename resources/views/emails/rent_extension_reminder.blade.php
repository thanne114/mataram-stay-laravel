<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Perpanjangan Sewa - Mataram Stay</title>
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
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #78706a; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 600;">Pengingat Tagihan Sewa</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 40px 32px 40px;">
                            <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>{{ $booking->user->name }}</strong>,</p>
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #3a302a;">Masa sewa Anda untuk kamar kos berikut akan berakhir dalam <strong>7 hari</strong> lagi. Agar kelangsungan tinggal Anda tidak terganggu, Anda dapat memperpanjang masa sewa sekarang.</p>
                            
                            <!-- Kos Detail Card -->
                            <div style="background-color: #faf5ee; border: 1px solid #ece6dc; border-radius: 12px; padding: 20px; margin-bottom: 30px;">
                                <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #c2652a; font-family: Georgia, serif;">Detail Kos Anda</h3>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px; line-height: 1.6;">
                                    <tr>
                                        <td width="40%" style="color: #78706a; padding-bottom: 6px;">Nama Kos:</td>
                                        <td style="font-weight: 600; padding-bottom: 6px;">{{ $booking->roomType->property->name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #78706a; padding-bottom: 6px;">Tipe Kamar:</td>
                                        <td style="font-weight: 600; padding-bottom: 6px;">{{ $booking->roomType->name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #78706a; padding-bottom: 6px;">Masa Sewa Saat Ini:</td>
                                        <td style="padding-bottom: 6px;">{{ $booking->check_in_date->format('d M Y') }} - {{ $booking->check_out_date->format('d M Y') }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Invoice Detail Card -->
                            <div style="background-color: #ffffff; border: 1px dashed #c2652a; border-radius: 12px; padding: 20px; margin-bottom: 30px;">
                                <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #c2652a; font-family: Georgia, serif;">Rincian Perpanjangan</h3>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px; line-height: 1.6;">
                                    <tr>
                                        <td width="40%" style="color: #78706a; padding-bottom: 6px;">Periode Baru:</td>
                                        <td style="font-weight: 600; padding-bottom: 6px;">
                                            {{ \Carbon\Carbon::parse($renewalBooking->check_in_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($renewalBooking->check_out_date)->format('d M Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color: #78706a; padding-bottom: 6px;">Durasi:</td>
                                        <td style="padding-bottom: 6px;">1 Bulan</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #78706a; padding-top: 6px; border-top: 1px solid #ece6dc; font-weight: bold;">Total Tagihan:</td>
                                        <td style="padding-top: 6px; border-top: 1px solid #ece6dc; font-weight: bold; color: #c2652a; font-size: 16px;">
                                            Rp {{ number_format($renewalBooking->total_price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Call to Action -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 32px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('booking.show', $renewalBooking->id) }}" style="background-color: #c2652a; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block; box-shadow: 0 4px 10px rgba(194, 101, 42, 0.2);">Perpanjang & Bayar Sekarang</a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 0 0 8px 0; font-size: 14px; line-height: 1.6; color: #78706a;"><strong>Catatan Penting:</strong></p>
                            <ul style="margin: 0 0 24px 0; padding-left: 20px; font-size: 13px; line-height: 1.6; color: #78706a;">
                                <li>Pembayaran dilakukan secara aman melalui payment gateway Midtrans.</li>
                                <li>Anda akan diminta untuk masuk ke akun Mataram Stay terlebih dahulu sebelum menyelesaikan pembayaran.</li>
                                <li>Jika Anda tidak melakukan pembayaran hingga masa sewa habis, kamar kos Anda dapat ditawarkan ke pencari kos lainnya.</li>
                            </ul>
                            
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
