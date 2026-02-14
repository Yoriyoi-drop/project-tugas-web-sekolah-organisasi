<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Organization Invitation</title>
</head>
<body>
    <h1>Organization Invitation</h1>
    <p>Hello,</p>
    <p>You have been invited to join <strong>{{ $data['organization_name'] ?? 'an organization' }}</strong> by {{ $data['inviter_name'] ?? 'a user' }}.</p>
    <p><a href="{{ $data['invitation_url'] ?? '#' }}">View Organization</a></p>
    <p>If you believe this was sent in error, please ignore this email.</p>
    <p>Best regards,<br>{{ config('app.name') }}</p>
</body>
</html>