<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Ready</title>
</head>
<body>
    <h1>Your Report is Ready</h1>
    <p>Hello,</p>
    <p>Your <strong>{{ $data['report_type'] ?? 'report' }}</strong> is ready for download.</p>
    <p><a href="{{ $data['download_url'] ?? '#' }}">Download Report</a></p>
    <p>Generated at: {{ $data['generated_at'] ?? now()->format('Y-m-d H:i:s') }}</p>
    <p>If you did not request this report, please ignore this email.</p>
    <p>Best regards,<br>{{ config('app.name') }}</p>
</body>
</html>