<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi - Mataram Stay</title>
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
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #78706a; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 600;">Permintaan Atur Ulang Kata Sandi</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 40px 32px 40px;">
                            <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>{{ $user->name }}</strong>,</p>
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #3a302a;">Kami menerima permintaan untuk menyetel ulang kata sandi akun Anda di <strong>Mataram Stay</strong>. Silakan klik tombol di bawah ini untuk melanjutkan:</p>
                            
                            <!-- CTA Button -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 32px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('password.reset', ['token' => $token, 'email' => $user->email]) }}" style="background-color: #c2652a; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block; box-shadow: 0 4px 10px rgba(194, 101, 42, 0.2);">Atur Ulang Kata Sandi</a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 0 0 8px 0; font-size: 14px; line-height: 1.6; color: #78706a;"><strong>Catatan Penting:</strong></p>
                            <ul style="margin: 0 0 24px 0; padding-left: 20px; font-size: 13px; line-height: 1.6; color: #78706a;">
                                <li>Tautan reset kata sandi ini hanya berlaku selama <strong>60 menit</strong> sejak email ini dikirimkan.</li>
                                <li>Jika Anda tidak merasa meminta penyetelan ulang kata sandi ini, tidak perlu melakukan tindakan apa pun. Kata sandi Anda akan tetap aman.</li>
                                <li>Tautan ini hanya dapat digunakan sebanyak satu kali.</li>
                            </ul>
                            
                            <p style="margin: 0 0 24px 0; font-size: 13px; line-height: 1.5; color: #78706a;">Jika Anda mengalami kendala dengan tombol di atas, salin dan tempel URL berikut ke peramban Anda:<br>
                            <a href="{{ route('password.reset', ['token' => $token, 'email' => $user->email]) }}" style="color: #c2652a; text-decoration: underline; word-break: break-all;">{{ route('password.reset', ['token' => $token, 'email' => $user->email]) }}</a></p>

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
