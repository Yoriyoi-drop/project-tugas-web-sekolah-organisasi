<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>System Alert</title>
</head>
<body>
    <h1 style="color: {{ $data['priority'] === 'high' ? 'red' : 'orange' }};">System Alert</h1>
    <p><strong>Subject:</strong> {{ $data['subject'] ?? 'System Alert' }}</p>
    <p><strong>Priority:</strong> {{ $data['priority'] ?? 'normal' }}</p>
    <p><strong>Message:</strong></p>
    <p>{{ $data['message'] ?? 'No message provided' }}</p>
    <p>This is an automated message from the system. Please take appropriate action.</p>
    <p>Best regards,<br>{{ config('app.name') }} System</p>
</body>
</html>