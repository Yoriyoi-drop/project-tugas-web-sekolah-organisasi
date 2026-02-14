<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Konfirmasi Pendaftaran PPDB - {{ config('app.name') }}</title>
</head>
<body>
    <h1>Konfirmasi Pendaftaran PPDB</h1>
    
    <p>Halo {{ $ppdb->name }},</p>
    
    <p>Terima kasih telah mendaftar melalui Program Penerimaan Siswa Didik (PPDB) di {{ config('app.name') }}.</p>
    
    <h2>Detail Pendaftaran:</h2>
    <ul>
        <li><strong>Nama:</strong> {{ $ppdb->name }}</li>
        <li><strong>Email:</strong> {{ $ppdb->email }}</li>
        <li><strong>No. Pendaftaran:</strong> {{ $registrationNumber }}</li>
        <li><strong>Status:</strong> {{ ucfirst($ppdb->status) }}</li>
        <li><strong>Tanggal Pendaftaran:</strong> {{ $ppdb->registration_date->format('d M Y') }}</li>
    </ul>
    
    <p>Tim kami akan segera meninjau pendaftaran Anda dan memberikan kabar lebih lanjut.</p>
    
    <p>Jika Anda memiliki pertanyaan, silakan hubungi kami melalui formulir kontak.</p>
    
    <p>Hormat kami,<br>
    Tim {{ config('app.name') }}</p>
</body>
</html>