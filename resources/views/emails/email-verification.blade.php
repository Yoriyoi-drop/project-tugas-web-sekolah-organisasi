<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Verification</title>
</head>
<body>
    <h1>Please Verify Your Email Address</h1>
    <p>Hello {{ $data['name'] ?? 'User' }},</p>
    <p>Thanks for registering! Please click the button below to verify your email address.</p>
    <p><a href="{{ $data['verification_url'] ?? '#' }}">Verify Email Address</a></p>
    <p>If you did not create an account, no further action is required.</p>
    <p>Best regards,<br>{{ config('app.name') }}</p>
</body>
</html>