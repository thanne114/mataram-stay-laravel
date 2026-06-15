<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Mataram Stay</title>
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
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #78706a; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 600;">Portal Verifikasi Akun</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 40px 32px 40px;">
                            <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>{{ $userName }}</strong>,</p>
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #3a302a;">Kami menerima permintaan untuk memverifikasi alamat email akun Anda di <strong>Mataram Stay</strong>. Silakan gunakan kode OTP berikut untuk menyelesaikan proses verifikasi:</p>
                            
                            <!-- OTP Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 24px 0 32px 0;">
                                <tr>
                                    <td align="center">
                                        <div style="background-color: #faf5ee; border: 1px dashed #c2652a; border-radius: 12px; padding: 20px 40px; display: inline-block; box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);">
                                            <span style="font-size: 36px; font-weight: 800; letter-spacing: 8px; color: #c2652a; font-family: Courier, monospace;">{{ $otp }}</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 0 0 8px 0; font-size: 14px; line-height: 1.6; color: #78706a;"><strong>Catatan Penting:</strong></p>
                            <ul style="margin: 0 0 24px 0; padding-left: 20px; font-size: 13px; line-height: 1.6; color: #78706a;">
                                <li>Kode OTP ini hanya berlaku selama <strong>10 menit</strong> sejak email ini dikirimkan.</li>
                                <li>Jangan bagikan kode OTP ini kepada siapa pun termasuk pihak Mataram Stay.</li>
                                <li>Jika Anda tidak merasa melakukan verifikasi ini, harap abaikan email ini.</li>
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
