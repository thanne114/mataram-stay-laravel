<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Moderasi - Mataram Stay</title>
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
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #78706a; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 600;">Keputusan Moderasi Platform</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 40px 32px 40px;">
                            
                            @if($type === 'seeker_verified')
                                <!-- Seeker Verified -->
                                <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>{{ $model->name }}</strong>,</p>
                                <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #3a302a;">Selamat! Verifikasi identitas Anda telah **disetujui oleh Administrator**. Sekarang Anda dapat melakukan reservasi kos di platform kami secara penuh dengan akun terverifikasi.</p>
                                
                                <div style="background-color: #e8f5e9; border-left: 4px solid #2e7d32; border-radius: 4px; padding: 15px; margin-bottom: 30px;">
                                    <p style="margin: 0; font-size: 14px; color: #2e7d32; font-weight: 600;">Status Akun Anda: Terverifikasi (Verified)</p>
                                </div>
                                
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 32px 0;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ url('/') }}" style="background-color: #c2652a; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block; box-shadow: 0 4px 10px rgba(194, 101, 42, 0.2);">Mulai Cari Kos</a>
                                        </td>
                                    </tr>
                                </table>

                            @elseif($type === 'verification_queue_admin')
                                <!-- Admin Queue Alert -->
                                <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>Administrator</strong>,</p>
                                <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #3a302a;">Pengguna baru telah mengajukan verifikasi identitas (KTP/Selfie). Silakan tinjau berkas dokumen tersebut pada panel admin.</p>
                                
                                <div style="background-color: #faf5ee; border: 1px solid #ece6dc; border-radius: 12px; padding: 20px; margin-bottom: 30px;">
                                    <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #c2652a; font-family: Georgia, serif;">Detail Pengguna</h3>
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px; line-height: 1.6;">
                                        <tr>
                                            <td width="40%" style="color: #78706a; padding-bottom: 6px;">Nama:</td>
                                            <td style="font-weight: 600; padding-bottom: 6px; color: #3a302a;">{{ $model->name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="color: #78706a; padding-bottom: 6px;">Email:</td>
                                            <td style="font-weight: 600; padding-bottom: 6px; color: #3a302a;">{{ $model->email }}</td>
                                        </tr>
                                        <tr>
                                            <td style="color: #78706a; padding-bottom: 6px;">Tipe Dokumen:</td>
                                            <td style="font-weight: 600; padding-bottom: 6px; color: #3a302a; text-transform: uppercase;">{{ $model->identity_type }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 32px 0;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ route('dashboard.admin') }}" style="background-color: #c2652a; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block; box-shadow: 0 4px 10px rgba(194, 101, 42, 0.2);">Buka Dashboard Admin</a>
                                        </td>
                                    </tr>
                                </table>

                            @elseif($type === 'property_approved')
                                <!-- Property Approved -->
                                <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>{{ $model->owner->name }}</strong>,</p>
                                <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #3a302a;">Kabar baik! Properti kos yang Anda daftarkan, <strong>{{ $model->name }}</strong>, telah disetujui oleh Administrator dan kini telah **diterbitkan secara publik** di aplikasi.</p>
                                
                                <div style="background-color: #e8f5e9; border-left: 4px solid #2e7d32; border-radius: 4px; padding: 15px; margin-bottom: 30px;">
                                    <p style="margin: 0; font-size: 14px; color: #2e7d32; font-weight: 600;">Status Listing: Published (Terbit)</p>
                                </div>

                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 32px 0;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ route('property.show', $model->slug) }}" style="background-color: #c2652a; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block; box-shadow: 0 4px 10px rgba(194, 101, 42, 0.2);">Lihat Halaman Properti</a>
                                        </td>
                                    </tr>
                                </table>

                            @elseif($type === 'property_submitted_admin')
                                <!-- Admin Property Moderation Alert -->
                                <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 1.5; color: #3a302a;">Halo, <strong>Administrator</strong>,</p>
                                <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #3a302a;">Pemilik kos baru saja mendaftarkan properti kos baru yang membutuhkan kurasi moderasi sebelum ditayangkan ke publik.</p>
                                
                                <div style="background-color: #faf5ee; border: 1px solid #ece6dc; border-radius: 12px; padding: 20px; margin-bottom: 30px;">
                                    <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #c2652a; font-family: Georgia, serif;">Detail Kos Baru</h3>
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 14px; line-height: 1.6;">
                                        <tr>
                                            <td width="40%" style="color: #78706a; padding-bottom: 6px;">Nama Kos:</td>
                                            <td style="font-weight: 600; padding-bottom: 6px; color: #3a302a;">{{ $model->name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="color: #78706a; padding-bottom: 6px;">Pemilik:</td>
                                            <td style="font-weight: 600; padding-bottom: 6px; color: #3a302a;">{{ $model->owner->name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="color: #78706a; padding-bottom: 6px;">Alamat Kos:</td>
                                            <td style="font-weight: 600; padding-bottom: 6px; color: #3a302a;">{{ $model->address }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 32px 0;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ route('dashboard.admin') }}" style="background-color: #c2652a; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block; box-shadow: 0 4px 10px rgba(194, 101, 42, 0.2);">Buka Dashboard Moderasi</a>
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
