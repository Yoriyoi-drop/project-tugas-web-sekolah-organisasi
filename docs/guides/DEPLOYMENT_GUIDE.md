# 🚀 Deployment Guide - Sekolah Organisasi

## 📋 Prerequisites

### System Requirements
- **PHP**: 8.2 or higher
- **Database**: MySQL 8.0+ / PostgreSQL 13+ / SQLite 3.8+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Memory**: Minimum 512MB RAM (1GB+ recommended)
- **Storage**: Minimum 2GB free space

### Required PHP Extensions
```bash
php-bcmath
php-ctype
php-fileinfo
php-json
php-mbstring
php-openssl
php-pdo
php-tokenizer
php-xml
php-curl
php-gd
php-zip
```

### Optional Extensions (Recommended)
```bash
php-redis (for caching and queues)
php-imagick (for image processing)
php-opcache (for performance)
```

## 🗂️ Project Structure

```
project-tugas-web-sekolah-organisasi/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   ├── Jobs/
│   └── ...
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── ...
├── public/
│   ├── css/
│   ├── js/
│   ├── icons/
│   └── ...
├── resources/
│   ├── views/
│   └── ...
├── routes/
├── storage/
├── vendor/
└── ...
```

## 🚀 Deployment Steps

### 1. Server Setup

#### For Ubuntu/Debian:
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Apache & PHP
sudo apt install apache2 php8.2 php8.2-fpm libapache2-mod-php8.2

# Install required PHP extensions
sudo apt install php8.2-mysql php8.2-pgsql php8.2-sqlite3 php8.2-bcmath php8.2-ctype php8.2-fileinfo php8.2-json php8.2-mbstring php8.2-openssl php8.2-tokenizer php8.2-xml php8.2-curl php8.2-gd php8.2-zip

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js (for asset compilation)
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Enable Apache modules
sudo a2enmod rewrite
sudo a2enmod headers
sudo a2enmod expires
```

#### For CentOS/RHEL:
```bash
# Install Apache & PHP
sudo yum install httpd php82 php82-php-fpm

# Install PHP extensions
sudo yum install php82-php-mysqlnd php82-php-pgsql php82-php-sqlite3 php82-php-bcmath php82-php-ctype php82-php-fileinfo php82-php-json php82-php-mbstring php82-php-openssl php82-php-tokenizer php82-php-xml php82-php-curl php82-php-gd php82-php-zip

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -
sudo yum install -y nodejs

# Enable Apache
sudo systemctl enable httpd
sudo systemctl start httpd
```

### 2. Database Setup

#### MySQL/MariaDB:
```bash
# Create database
mysql -u root -p
CREATE DATABASE sekolah_organisasi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sekolah_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON sekolah_organisasi.* TO 'sekolah_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### PostgreSQL:
```bash
# Create database
sudo -u postgres psql
CREATE DATABASE sekolah_organisasi;
CREATE USER sekolah_user WITH PASSWORD 'your_secure_password';
GRANT ALL PRIVILEGES ON DATABASE sekolah_organisasi TO sekolah_user;
\q
```

#### SQLite (Development):
```bash
# SQLite will be created automatically
# Just ensure the directory is writable
mkdir -p database
chmod 755 database
```

### 3. Project Deployment

#### Clone/Upload Project:
```bash
# Option 1: Git Clone
cd /var/www/html
sudo git clone <your-repository-url> sekolah-organisasi

# Option 2: Upload files
# Upload project files to /var/www/html/sekolah-organisasi
```

#### Set Permissions:
```bash
cd /var/www/html/sekolah-organisasi

# Set ownership
sudo chown -R www-data:www-data .

# Set permissions
sudo find . -type f -exec chmod 644 {} \;
sudo find . -type d -exec chmod 755 {} \;

# Special permissions for storage
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# Make storage writable
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache
```

#### Install Dependencies:
```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node.js dependencies
npm install

# Build assets (if using Vite/Mix)
npm run build
```

### 4. Environment Configuration

#### Create Environment File:
```bash
cp .env.example .env
sudo nano .env
```

#### Configure .env:
```env
APP_NAME="Sekolah Organisasi"
APP_ENV=production
APP_KEY=base64:your_generated_key_here
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sekolah_organisasi
DB_USERNAME=sekolah_user
DB_PASSWORD=your_secure_password

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Queue (optional)
QUEUE_CONNECTION=sync

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Filesystem
FILESYSTEM_DISK=local

# Security
BCRYPT_ROUNDS=12

# Performance
OPCACHE_ENABLE=1
```

#### Generate Application Key:
```bash
php artisan key:generate
```

### 5. Database Setup

#### Run Migrations:
```bash
php artisan migrate --force
```

#### Seed Database:
```bash
php artisan db:seed --force
```

#### Create Storage Link:
```bash
php artisan storage:link
```

### 6. Web Server Configuration

#### Apache Configuration:
```apache
# Create virtual host file
sudo nano /etc/apache2/sites-available/sekolah-organisasi.conf
```

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/html/sekolah-organisasi/public

    <Directory /var/www/html/sekolah-organisasi/public>
        AllowOverride All
        Require all granted
    </Directory>

    # Security Headers
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set X-Content-Type-Options "nosniff"
    Header always set Referrer-Policy "no-referrer-when-downgrade"
    Header always set Content-Security-Policy "default-src 'self' https: data: 'unsafe-inline' 'unsafe-eval'"

    # Performance Headers
    Header always set Cache-Control "public, max-age=31536000"
    Header always set Expires "access plus 1 year"

    ErrorLog ${APACHE_LOG_DIR}/sekolah-organisasi_error.log
    CustomLog ${APACHE_LOG_DIR}/sekolah-organisasi_access.log combined
</VirtualHost>
```

#### Enable Site:
```bash
sudo a2ensite sekolah-organisasi.conf
sudo a2dissite 000-default.conf
sudo systemctl reload apache2
```

#### Nginx Configuration:
```nginx
# Create server block file
sudo nano /etc/nginx/sites-available/sekolah-organisasi
```

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/html/sekolah-organisasi/public;
    index index.php index.html index.htm;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' https: data: 'unsafe-inline' 'unsafe-eval'" always;

    # Performance Headers
    add_header Cache-Control "public, max-age=31536000" always;
    add_header Expires "access plus 1 year" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    # Static File Caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    error_log /var/log/nginx/sekolah-organisasi_error.log;
    access_log /var/log/nginx/sekolah-organisasi_access.log;
}
```

#### Enable Site:
```bash
sudo ln -s /etc/nginx/sites-available/sekolah-organisasi /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 7. SSL Certificate (HTTPS)

#### Let's Encrypt (Recommended):
```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Get SSL Certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

#### Manual SSL:
1. Upload certificate files to `/etc/ssl/certs/`
2. Update Apache/Nginx configuration
3. Restart web server

### 8. Performance Optimization

#### PHP Configuration:
```bash
# Edit PHP configuration
sudo nano /etc/php/8.2/apache2/php.ini
```

```ini
; Performance Settings
memory_limit = 256M
max_execution_time = 300
max_input_time = 300
upload_max_filesize = 64M
post_max_size = 64M
max_file_uploads = 20

; OPcache Settings
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

#### Laravel Optimization:
```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Clear caches if needed
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### 9. Queue Setup (Optional)

#### Redis Installation:
```bash
# Install Redis
sudo apt install redis-server

# Configure Redis
sudo nano /etc/redis/redis.conf
# Set: supervised systemd

# Start Redis
sudo systemctl start redis-server
sudo systemctl enable redis-server
```

#### Configure Laravel Queue:
```env
# In .env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

#### Start Queue Worker:
```bash
# Create supervisor configuration
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/sekolah-organisasi/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/log/laravel-worker.log
stopwaitsecs=3600
```

```bash
# Start supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### 10. Cron Jobs

#### Laravel Scheduler:
```bash
sudo crontab -e
```

```cron
# Laravel Scheduler
* * * * * cd /var/www/html/sekolah-organisasi && php artisan schedule:run >> /dev/null 2>&1

# Backup Database (daily at 2 AM)
0 2 * * * mysqldump -u sekolah_user -p'your_password' sekolah_organisasi > /backups/sekolah_organisasi_$(date +\%Y\%m\%d).sql

# Clean up old backups (keep 30 days)
0 3 * * * find /backups -name "*.sql" -mtime +30 -delete

# Clear old logs (weekly)
0 4 * * 0 find /var/log -name "*.log" -mtime +7 -delete
```

### 11. Security Hardening

#### File Permissions:
```bash
# Secure sensitive files
sudo chmod 600 .env
sudo chmod 600 storage/oauth-*.key

# Remove development files
sudo rm -rf .git node_modules
sudo rm composer.json composer.lock package.json package-lock.json webpack.mix.js
```

#### Firewall Setup:
```bash
# Install UFW
sudo apt install ufw

# Default policies
sudo ufw default deny incoming
sudo ufw default allow outgoing

# Allow SSH, HTTP, HTTPS
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw allow 'Apache Full'

# Enable firewall
sudo ufw enable
```

### 12. Monitoring & Logging

#### Log Rotation:
```bash
# Create logrotate config
sudo nano /etc/logrotate.d/sekolah-organisasi
```

```
/var/www/html/sekolah-organisasi/storage/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
    postrotate
        systemctl reload apache2
    endscript
}
```

#### Health Check:
```bash
# Create health check endpoint
# Route::get('/health', function () {
#     return response()->json(['status' => 'ok', 'timestamp' => now()]);
# });
```

## 🔧 Troubleshooting

### Common Issues

#### 500 Internal Server Error:
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check web server logs
tail -f /var/log/apache2/error.log
# or
tail -f /var/log/nginx/error.log

# Check permissions
ls -la storage/
ls -la bootstrap/cache/
```

#### Database Connection Error:
```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check .env configuration
cat .env | grep DB_
```

#### Asset Loading Issues:
```bash
# Clear and rebuild assets
php artisan view:clear
php artisan cache:clear
npm run build

# Check storage link
ls -la public/storage
```

#### Permission Issues:
```bash
# Reset permissions
sudo chown -R www-data:www-data .
sudo find . -type f -exec chmod 644 {} \;
sudo find . -type d -exec chmod 755 {} \;
sudo chmod -R 775 storage bootstrap/cache
```

### Performance Issues

#### Slow Page Load:
```bash
# Enable OPcache
php -m | grep opcache

# Check database queries
php artisan tinker
>>> DB::enableQueryLog();
>>> // Run some queries
>>> DB::getQueryLog();
```

#### High Memory Usage:
```bash
# Check PHP memory limit
php -i | grep memory_limit

# Monitor processes
top -p $(pgrep php-fpm)
```

## 📊 Post-Deployment Checklist

### ✅ Security
- [ ] Environment variables configured
- [ ] File permissions set correctly
- [ ] SSL certificate installed
- [ ] Firewall configured
- [ ] Debug mode disabled

### ✅ Performance
- [ ] PHP OPcache enabled
- [ ] Laravel caches optimized
- [ ] Gzip compression enabled
- [ ] Static file caching configured
- [ ] Database queries optimized

### ✅ Functionality
- [ ] Database migrations run
- [ ] Seeders executed
- [ ] Storage link created
- [ ] Queue workers running (if used)
- [ ] Cron jobs configured

### ✅ Monitoring
- [ ] Error logging configured
- [ ] Health check endpoint working
- [ ] Log rotation set up
- [ ] Backup system configured
- [ ] Performance monitoring enabled

## 🚀 Going Live

### Final Steps:
1. **Test all functionality** in production environment
2. **Monitor performance** for first 24 hours
3. **Check error logs** regularly
4. **Backup system** verified
5. **SSL certificate** valid
6. **Domain DNS** properly configured

### Launch Checklist:
- [ ] DNS propagation complete
- [ ] SSL certificate active
- [ ] All pages loading correctly
- [ ] Forms submitting properly
- [ ] Database connections working
- [ ] File uploads functional
- [ ] Email sending working
- [ ] Mobile responsive working

---

**🎉 Congratulations!** Your Sekolah Organisasi system is now deployed and ready for production use.

For support and maintenance, refer to the documentation files in the project root directory.
