<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode Verifikasi Password</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #007bff;">MA NU Nusantara</h1>
        </div>
        
        <h2>Kode Verifikasi Ubah Password</h2>
        
        <p>Halo {{ $userName }},</p>
        
        <p>Anda telah meminta untuk mengubah password akun Anda. Gunakan kode verifikasi berikut:</p>
        
        <div style="background: #f8f9fa; padding: 20px; text-align: center; margin: 20px 0; border-radius: 5px;">
            <h1 style="color: #007bff; font-size: 32px; margin: 0; letter-spacing: 5px;">{{ $code }}</h1>
        </div>
        
        <p><strong>Penting:</strong></p>
        <ul>
            <li>Kode ini berlaku selama 10 menit</li>
            <li>Jangan bagikan kode ini kepada siapapun</li>
            <li>Jika Anda tidak meminta perubahan password, abaikan email ini</li>
        </ul>
        
        <p>Terima kasih,<br>Tim MA NU Nusantara</p>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
        <p style="font-size: 12px; color: #666;">
            Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
        </p>
    </div>
</body>
</html>