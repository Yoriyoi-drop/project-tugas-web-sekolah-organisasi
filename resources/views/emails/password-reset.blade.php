<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset</title>
</head>
<body>
    <h1>Password Reset Request</h1>
    <p>Hello {{ $data['name'] ?? 'User' }},</p>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <p><a href="{{ $data['reset_url'] ?? '#' }}">Reset Password</a></p>
    <p>This password reset link will expire in 60 minutes.</p>
    <p>If you did not request a password reset, no further action is required.</p>
    <p>Best regards,<br>{{ config('app.name') }}</p>
</body>
</html>