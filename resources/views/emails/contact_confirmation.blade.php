<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Konfirmasi Pesan - {{ config('app.name') }}</title>
</head>
<body>
    <h1>Konfirmasi Pesan</h1>
    
    <p>Halo {{ $contact->name }},</p>
    
    <p>Terima kasih telah menghubungi kami. Kami telah menerima pesan Anda:</p>
    
    <p><strong>Subjek:</strong> {{ $contact->subject }}</p>
    <p><strong>Pesan:</strong> {{ $contact->message }}</p>
    
    <p>Tim kami akan segera menanggapi pesan Anda.</p>
    
    <p>Hormat kami,<br>
    Tim {{ config('app.name') }}</p>
</body>
</html>