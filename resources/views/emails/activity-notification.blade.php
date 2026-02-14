<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Activity Notification</title>
</head>
<body>
    <h1>New Activity Notification</h1>
    <p>Hello {{ $data['member_name'] ?? 'Member' }},</p>
    <p>A new activity has been scheduled:</p>
    <h3>{{ $data['activity_title'] ?? 'Untitled Activity' }}</h3>
    <p><strong>Date:</strong> {{ $data['activity_date'] ?? 'TBD' }}</p>
    <p><strong>Description:</strong> {{ $data['activity_description'] ?? 'No description provided' }}</p>
    <p><strong>Organization:</strong> {{ $data['organization_name'] ?? 'Unknown Organization' }}</p>
    <p><a href="#">View Activity Details</a></p>
    <p>Best regards,<br>{{ config('app.name') }}</p>
</body>
</html>