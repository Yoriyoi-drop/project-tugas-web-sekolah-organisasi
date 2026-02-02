# Backup and Monitoring Setup

## Overview

This document explains the backup and monitoring setup for the Madrasah Aliyah Nusantara application.

## Automated Backups

### Database Backup Command

The application includes an automated database backup command:

```bash
# Create basic backup
php artisan backup:database

# Create compressed backup
php artisan backup:database --compress
```

### Setting Up Scheduled Backups

Add the following to your `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Daily database backup at 2 AM
    $schedule->command('backup:database --compress')
             ->dailyAt('02:00')
             ->onSuccess(function () {
                 Log::info('Daily backup completed successfully');
             })
             ->onFailure(function () {
                 Log::error('Daily backup failed');
             });

    // System health monitoring every hour
    $schedule->command('monitor:health')
             ->hourly()
             ->onFailure(function () {
                 // Send critical alert
             });
}
```

### Backup Storage

- **Location**: `storage/app/backups/`
- **Retention**: 7 days (configurable)
- **Compression**: Optional gzip compression
- **Formats**: SQL dumps for MySQL, SQLite, PostgreSQL

## System Monitoring

### Health Check Command

Monitor system resources and application health:

```bash
# Check system health with default threshold (90%)
php artisan monitor:health

# Custom alert threshold
php artisan monitor:health --alert-threshold=80
```

### Health Check Endpoints

API endpoints for external monitoring:

- **Basic Health**: `GET /api/health`
- **Detailed Health**: `GET /api/health/detailed`

### Monitoring Metrics

The system monitors:

1. **Disk Space Usage**
   - Total, used, and free space
   - Percentage usage
   - Alert threshold configurable

2. **Memory Usage**
   - Current memory usage
   - PHP memory limit
   - Percentage usage

3. **Database Connectivity**
   - Connection status
   - Response time

4. **Cache System**
   - Read/write functionality
   - Cache hit rates

## Setting Up Cron Jobs

### Linux Cron Setup

```bash
# Edit crontab
crontab -e

# Add Laravel scheduler
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### Example Cron Schedule

```bash
# Laravel scheduler (every minute)
* * * * * cd /var/www/madrasah && php artisan schedule:run >> /dev/null 2>&1

# Manual backup (daily at 2 AM)
0 2 * * * cd /var/www/madrasah && php artisan backup:database --compress

# Health check (every 5 minutes)
*/5 * * * * cd /var/www/madrasah && php artisan monitor:health --alert-threshold=85
```

## External Monitoring Integration

### Uptime Monitoring

Configure external monitoring services to check:

- `https://your-domain.com/api/health` - Basic health
- `https://your-domain.com/api/health/detailed` - Detailed health

### Example Monitoring Configuration

#### Uptime Robot
- Monitor Type: HTTP
- URL: `https://your-domain.com/api/health`
- Expected Status: 200 OK
- Alert on: Down, 5 consecutive failures

#### Prometheus Metrics (Optional)

Add metrics endpoint for Prometheus:

```php
// routes/api.php
Route::get('/metrics', function () {
    // Return Prometheus-formatted metrics
    return response()->view('metrics', [
        'disk_usage' => getDiskUsage(),
        'memory_usage' => getMemoryUsage(),
    ]);
});
```

## Alert Configuration

### Email Alerts

Configure email alerts in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=alerts@yourdomain.com
```

### Slack Integration (Optional)

Add Slack webhook integration:

```php
// In MonitorSystemHealth command
protected function sendSlackAlert($message)
{
    $webhook = env('SLACK_WEBHOOK_URL');
    
    Http::post($webhook, [
        'text' => $message,
        'channel' => '#alerts',
        'username' => 'System Monitor'
    ]);
}
```

## Log Management

### Log Rotation

Configure log rotation in `config/logging.php`:

```php
'daily' => [
    'driver' => 'daily',
    'path' => storage_path('logs/laravel.log'),
    'level' => env('LOG_LEVEL', 'debug'),
    'days' => 14,
],
```

### Log Monitoring

Monitor these log files:

- `storage/logs/laravel.log` - Application logs
- `storage/logs/backup.log` - Backup logs
- `storage/logs/monitoring.log` - Health check logs

## Recovery Procedures

### Database Recovery

```bash
# List available backups
ls -la storage/app/backups/

# Restore from backup
mysql -u username -p database_name < backup_file.sql

# For compressed backups
gunzip < backup_file.sql.gz | mysql -u username -p database_name
```

### File System Recovery

```bash
# Restore from backup storage
cp -r /backup/storage/app/* storage/app/

# Restore uploaded files
cp -r /backup/public/uploads/* public/uploads/
```

## Security Considerations

1. **Backup Encryption**: Consider encrypting sensitive backups
2. **Access Control**: Restrict backup directory access
3. **Offsite Storage**: Store backups in multiple locations
4. **Regular Testing**: Test backup restoration procedures

## Performance Impact

- **Backup Process**: Minimal impact during off-peak hours
- **Monitoring**: Very low overhead (< 1% CPU)
- **Storage**: Monitor backup storage growth

## Troubleshooting

### Common Issues

1. **Backup Fails**:
   - Check database credentials
   - Verify disk space
   - Check database permissions

2. **Monitoring Alerts**:
   - Verify thresholds
   - Check system resources
   - Review log files

3. **Cron Issues**:
   - Verify cron service status
   - Check PHP path
   - Review file permissions

### Debug Commands

```bash
# Test backup manually
php artisan backup:database --verbose

# Check system resources
df -h
free -h

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo()
```
