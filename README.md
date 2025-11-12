# 🎓 Madrasah Aliyah Nusantara

Modern responsive website built with Laravel & Bootstrap 5

## 🌟 Features

- ✅ **Modern Contact Page** - Teal gradient design with interactive form
- ✅ **Password Verification** - 6-digit code with countdown timer
- ✅ **Fully Responsive** - Mobile-first Bootstrap 5 design
- ✅ **Security Features** - 2FA, rate limiting, audit logging
- ✅ **Admin Panel** - Organization & user management
- ✅ **Avatar Upload** - Profile picture with fallback handling
- ✅ **Role Management** - Multi-system role/permission support

## 🎨 Design Highlights

- **Primary Gradient**: `linear-gradient(135deg, #009688 0%, #00796B 100%)`
- **Accent Color**: `#00BFA5`
- **Typography**: Poppins font with fluid scaling
- **Icons**: Font Awesome 6.4.0

## 🚀 Installation

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js (optional, for asset compilation)
- Database (SQLite, MySQL, PostgreSQL)

### Linux Installation

```bash
# Clone repository
git clone https://github.com/Yoriyoi-drop/organisasi-sekolah-web-2.0.git
cd organisasi-sekolah-web-2.0

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database (using SQLite as default)
touch database/database.sqlite

# Run migrations
php artisan migrate --seed

# Install frontend assets (optional)
npm install && npm run dev

# Start development server
php artisan serve
```

### Windows Installation

```cmd
# Clone repository
git clone https://github.com/Yoriyoi-drop/organisasi-sekolah-web-2.0.git
cd organisasi-sekolah-web-2.0

# Install dependencies
composer install

# Setup environment
copy .env.example .env
php artisan key:generate

# Configure database (using SQLite as default)
type nul > database/database.sqlite

# Run migrations
php artisan migrate --seed

# Install frontend assets (optional)
npm install && npm run dev

# Start development server
php artisan serve
```

### macOS Installation

```bash
# Clone repository
git clone https://github.com/Yoriyoi-drop/organisasi-sekolah-web-2.0.git
cd organisasi-sekolah-web-2.0

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database (using SQLite as default)
touch database/database.sqlite

# Run migrations
php artisan migrate --seed

# Install frontend assets (optional)
npm install && npm run dev

# Start development server
php artisan serve
```

## 📱 Responsive Breakpoints

- Mobile: 320px+
- Tablet: 768px+
- Desktop: 1200px+

## 🛠️ Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Bootstrap 5, Font Awesome, Vite
- **Database**: SQLite/MySQL/PostgreSQL
- **Role Management**: Spatie Laravel Permission
- **Deployment**: Vercel/Netlify ready

## 📝 Version 1.15 Updates & Fixes

### 🔧 Bug Fixes
- **Fixed avatar upload blank issue**: Added proper error handling and fallback avatar
  - Created `getAvatarUrlAttribute()` accessor with file existence check
  - Added proper error handling in `AvatarController`
  - Created default avatar SVG as fallback
- **Resolved security audit repository issues**: Fixed raw SQL queries with safer alternatives
  - Replaced `whereRaw("json_extract(data, '$.status') = ?", ...)` with `whereJsonContains`
  - Improved error handling in security logging
- **Fixed SecurityLog cache clearing**: Removed excessive cache clearing on every log entry
  - Improved performance by removing automatic cache clear on SecurityLog model events
- **Fixed IP change detection**: Improved security service to avoid exposing sensitive IPs
  - Changed to log only timestamps instead of actual IP addresses

### 🛡️ Security Improvements
- **Enhanced role/permission checking**: Improved `hasRole()` and `hasAbility()` functions
  - Added proper exception handling for database queries
  - Improved fallback mechanism between legacy and Spatie systems
  - Added `hasAnyRole()` method for middleware compatibility
- **Improved session security**: Enhanced secure session validation
- **Better input sanitization**: Added normalization for sensitive data

### ⚡ Performance Improvements
- **Reduced SecurityLog cache clearing overhead**: Removed automatic cache clear on every log
- **Optimized role checking**: More efficient database queries
- **Better error handling**: Prevents crashes during permission checks

### 🎨 UI/UX Improvements
- **Better avatar display**: More reliable avatar loading with fallback
  - Uses `$user->avatar_url` accessor instead of direct `Storage::url()` calls
  - Shows default avatar when user avatar is missing
- **Enhanced error messages**: More user-friendly error feedback
- **Improved accessibility**: Better alt tags and semantic HTML

### 🏗️ Architecture Enhancements
- **Better role system consistency**: Hybrid approach supporting both legacy and Spatie systems
- **Added sync functions**: `syncLegacyRolesToSpatie()` for migration support
- **Improved error logging**: More comprehensive error tracking across systems
- **Backward compatibility maintained**: Still supports legacy role/ability tables

## 📸 Screenshots

### Desktop
![Contact Page]
![Password Verification]

### Mobile
![Contact Mobile]
![Password Mobile]

## 🔐 Security Features

- **Two-Factor Authentication** - Optional 2FA for enhanced security
- **Rate Limiting** - Protection against brute force attacks
- **IP Change Detection** - Monitors for suspicious location changes
- **Audit Logging** - Comprehensive security event tracking
- **Sensitive Data Encryption** - Phone numbers and addresses encrypted at rest
- **Role-Based Access Control** - Fine-grained permission system

## 🚀 Deployment

### Local Development
```bash
php artisan serve
```

### Production Deployment
1. Set environment variables appropriately
2. Run migrations: `php artisan migrate --force`
3. Build assets: `npm run build`
4. Configure web server to point to `/public` directory

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/AmazingFeature`
3. Commit changes: `git commit -m 'Add some AmazingFeature'`
4. Push to the branch: `git push origin feature/AmazingFeature`
5. Open a Pull Request

## 📧 Contact

Built for Madrasah Aliyah Nusantara - Modern Islamic Education