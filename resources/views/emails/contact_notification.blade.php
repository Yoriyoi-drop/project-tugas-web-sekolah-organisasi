<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pesan Baru dari Formulir Kontak - {{ config('app.name') }}</title>
</head>
<body>
    <h1>Pesan Baru dari Formulir Kontak</h1>
    
    <p>Anda menerima pesan baru dari formulir kontak:</p>
    
    <p><strong>Nama:</strong> {{ $contact->name }}</p>
    <p><strong>Email:</strong> {{ $contact->email }}</p>
    @if($contact->phone)
    <p><strong>Telepon:</strong> {{ $contact->phone }}</p>
    @endif
    <p><strong>Subjek:</strong> {{ $contact->subject }}</p>
    <p><strong>Pesan:</strong> {{ $contact->message }}</p>
    @if($contact->organization)
    <p><strong>Organisasi:</strong> {{ $contact->organization }}</p>
    @endif
    
    <p>Hormat kami,<br>
    Sistem {{ config('app.name') }}</p>
</body>
</html>