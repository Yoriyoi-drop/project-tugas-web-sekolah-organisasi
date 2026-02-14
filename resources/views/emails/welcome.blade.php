<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome Email</title>
</head>
<body>
    <h1>Welcome, {{ $data['name'] ?? 'there' }}!</h1>
    <p>Thank you for registering with us. Your account has been created successfully.</p>
    <p>You can now log in to your account using your credentials.</p>
    <p><a href="{{ $data['login_url'] ?? '#' }}">Login to your account</a></p>
    <p>If you have any questions, feel free to contact us.</p>
    <p>Best regards,<br>{{ config('app.name') }}</p>
</body>
</html>