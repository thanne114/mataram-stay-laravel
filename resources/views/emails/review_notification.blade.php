<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan Baru Diterima - Mataram Stay</title>
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
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #78706a; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 600;">Notifikasi Masukan Pengguna</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 40px 32px 40px;">
                            <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>{{ $review->property->owner->name }}</strong>,</p>
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #3a302a;">Selamat! Salah satu penyewa Anda baru saja membagikan ulasan baru mengenai pengalaman tinggal mereka di properti kos Anda, <strong>{{ $review->property->name }}</strong>.</p>
                            
                            <!-- Review Card -->
                            <div style="background-color: #faf5ee; border: 1px solid #ece6dc; border-radius: 12px; padding: 24px; margin-bottom: 30px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td style="padding-bottom: 12px;">
                                            <span style="font-size: 14px; font-weight: bold; color: #3a302a;">{{ $review->user->name }}</span>
                                            <span style="font-size: 12px; color: #78706a; margin-left: 8px;">(Penyewa)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 16px;">
                                            <!-- Stars representation -->
                                            <div style="display: inline-block;">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <span style="color: #ffb300; font-size: 18px;">★</span>
                                                    @else
                                                        <span style="color: #d8d0c8; font-size: 18px;">★</span>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span style="font-size: 14px; font-weight: bold; color: #c2652a; margin-left: 8px; vertical-align: middle;">{{ $review->rating }} / 5</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 14px; line-height: 1.6; color: #3a302a; font-style: italic; background-color: #ffffff; border: 1px solid #ece6dc; border-radius: 8px; padding: 15px;">
                                            "{{ $review->comment ?? 'Tidak ada ulasan tertulis.' }}"
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; color: #78706a;">Gunakan masukan ini untuk terus menjaga performa kebersihan, fasilitas, dan kenyamanan kos Anda agar tetap disukai oleh para penyewa selanjutnya!</p>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 32px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('property.show', $review->property->slug) }}" style="background-color: #c2652a; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block; box-shadow: 0 4px 10px rgba(194, 101, 42, 0.2);">Lihat Ulasan di Halaman Kos</a>
                                    </td>
                                </tr>
                            </table>

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
