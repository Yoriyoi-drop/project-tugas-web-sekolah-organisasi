# 🎓 Madrasah Aliyah Nusantara

Modern responsive website built with Laravel & Bootstrap 5

## 🌟 Features

- ✅ **Modern Contact Page** - Teal gradient design with interactive form
- ✅ **Password Verification** - 6-digit code with countdown timer
- ✅ **Student Registration System** - Complete account registration with approval workflow
- ✅ **PPDB Management** - Online student admission system
- ✅ **Fully Responsive** - Mobile-first Bootstrap 5 design
- ✅ **Security Features** - 2FA, rate limiting, audit logging
- ✅ **Admin Panel** - Organization & user management
- ✅ **Avatar Upload** - Profile picture with fallback handling
- ✅ **Role Management** - Multi-system role/permission support
- ✅ **Offline CSS Assets** - All CSS resources (Google Fonts, Bootstrap Icons from npm, Font Awesome) available locally

## 🎨 Design Highlights

- **Primary Gradient**: `linear-gradient(135deg, #009688 0%, #00796B 100%)`
- **Accent Color**: `#00BFA5`
- **Typography**: Poppins font with fluid scaling
- **Icons**: Font Awesome 6.4.0
- **Offline CSS Assets**: All CSS files including Google Fonts, Bootstrap Icons (from npm), and Font Awesome are stored locally for improved performance and offline capability

## 🚀 Installation

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js >= 20 (for asset compilation)
- Database (SQLite, MySQL, PostgreSQL)
- Docker & Docker Compose (optional, for containerized deployment)

### 🐳 Docker Installation (Recommended)

The project includes a complete Docker setup with production-ready multi-stage builds.

#### Quick Start

```bash
# Clone repository
git clone https://github.com/Yoriyoi-drop/project-tugas-web-sekolah-organisasi.git
cd project-tugas-web-sekolah-organisasi

# Development (with hot reload)
make build-dev && make dev

# Production
make build && make up
```

#### Docker Services

| Service | Image | Port | Description |
|---------|-------|------|-------------|
| **app** | Custom PHP 8.4 Alpine | 8000 | Laravel app with Nginx + PHP-FPM |
| **mysql** | MySQL 8.4 | 3306 | Database server |
| **redis** | Redis 7.4 Alpine | 6379 | Cache & queue driver |
| **mailpit** | Axllent Mailpit | 1025/8025 | Email testing (dev only) |
| **phpmyadmin** | phpMyAdmin | 8080 | Database management (dev only) |

#### Makefile Commands

```bash
make help              # Show all available commands
make build-dev         # Build development containers
make dev               # Start development environment
make stop-dev          # Stop development environment
make build             # Build production containers
make up                # Start production environment
make down              # Stop production environment
make restart           # Restart production environment
make logs              # View production logs
make logs-dev          # View development logs
make shell             # Open shell in production app
make shell-dev         # Open shell in development app
make migrate           # Run database migrations
make seed              # Run database seeders
make fresh             # Fresh migration with seeding
make test              # Run tests
make clean             # Remove all containers and volumes
```

#### Docker Architecture

```
┌─────────────────────────────────────────────────┐
│                 Docker Network                   │
│                                                  │
│  ┌──────────┐    ┌──────────┐    ┌──────────┐   │
│  │  Nginx   │───▶│ PHP-FPM  │───▶│  MySQL   │   │
│  │  :8000   │    │  :9000   │    │  :3306   │   │
│  └──────────┘    └──────────┘    └──────────┘   │
│                      │                           │
│                      ▼                           │
│                ┌──────────┐                      │
│                │  Redis   │                      │
│                │  :6379   │                      │
│                └──────────┘                      │
└─────────────────────────────────────────────────┘
```

#### Production Build Features

- **Multi-stage build** — Composer → Node.js → PHP 8.4 Alpine (minimal image size)
- **Supervisor** — Manages Nginx, PHP-FPM, and queue workers in one container
- **Auto-migration** — Optional `AUTO_MIGRATE=true` env variable
- **Health checks** — Built-in health check endpoint (`/up`)
- **Security headers** — X-Frame-Options, X-Content-Type-Options, XSS Protection
- **Gzip compression** — Optimized static asset delivery
- **Resource limits** — CPU and memory limits defined in `docker-compose.prod.yml`

#### Environment Variables for Docker

```env
# App
APP_PORT=8000
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sekolah
DB_USERNAME=sekolah
DB_PASSWORD=secret

# Redis
REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=

# Cache & Queue
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Auto Migration
AUTO_MIGRATE=true
```

### Development Quality Tools

The project includes several quality assurance tools:

- **PHPStan** (Level 5) - Static analysis for finding bugs and inconsistencies
- **PHP_CodeSniffer** - Ensures code follows PSR-12 standards
- **PHP-CS-Fixer** - Automatically fixes coding standards issues
- **CI Workflow** - Automated testing and code quality checks

### Manual Installation (Without Docker)

#### Linux Installation

```bash
# Clone repository
git clone https://github.com/Yoriyoi-drop/project-tugas-web-sekolah-organisasi.git
cd project-tugas-web-sekolah-organisasi

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

#### Windows Installation

```cmd
# Clone repository
git clone https://github.com/Yoriyoi-drop/project-tugas-web-sekolah-organisasi.git
cd project-tugas-web-sekolah-organisasi

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

#### macOS Installation

```bash
# Clone repository
git clone https://github.com/Yoriyoi-drop/project-tugas-web-sekolah-organisasi.git
cd project-tugas-web-sekolah-organisasi

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
- **CSS Assets**: Google Fonts, Bootstrap Icons (from npm), Font Awesome (all stored locally for offline capability)
- **Database**: SQLite/MySQL/PostgreSQL
- **Cache & Queue**: Redis 7.4
- **Role Management**: Spatie Laravel Permission
- **Containerization**: Docker & Docker Compose
- **Web Server**: Nginx + PHP-FPM 8.4
- **Process Manager**: Supervisor
- **Deployment**: Docker (production-ready), Vercel/Netlify ready

## 🎓 Student Registration System

### Overview
Complete student account registration system with admin approval workflow, similar to PPDB but focused on creating user accounts for the learning management system.

### Features
- **Public Registration Form** - Modern, responsive registration interface
- **Admin Approval System** - Admin dashboard to review and approve/reject registrations
- **Automatic Account Creation** - User accounts created automatically upon approval
- **Role Assignment** - Approved students get "calon siswa" role
- **Email Validation** - Unique email validation across registration and user tables
- **Comprehensive Validation** - NIK, phone, and data validation
- **Modern UI/UX** - Animated hero section, floating cards, and smooth transitions

### Routes
- `/pendaftaran-siswa` - Registration information page
- `/pendaftaran-siswa/daftar` - Registration form
- `/admin/student-registrations` - Admin management dashboard

### Database Schema
```sql
student_registrations:
- id, name, nik, email, birth_date, birth_place
- gender, address, phone, parent_name, parent_phone
- previous_school, desired_major
- status (pending/approved/rejected)
- notes, approved_at, rejected_at
- approved_by, rejected_by
- created_at, updated_at
```

## 📝 Version 1.17 - Bug Fixes & Synchronization

### 🐛 Critical Bug Fixes

#### 1. **API Routes Not Loading** ⚠️ CRITICAL
- **Problem**: `routes/api.php` tidak ter-load di `bootstrap/app.php`
- **Impact**: Semua API endpoints (Students V1/V2, Health, Auth) tidak berfungsi
- **Fix**: Menambahkan `api: __DIR__.'/../routes/api.php'` ke `withRouting()` di bootstrap/app.php
- **File**: `bootstrap/app.php`

#### 2. **Missing Controller Methods** 🔴
- **Admin OrganizationController**: Missing `show()` method tapi route ada di web.php
  - **Fix**: Menambahkan method `show()` dengan data organization, memberStats, leadershipMembers
  - **File**: `app/Http/Controllers/Admin/OrganizationController.php`

- **Admin PostController**: Missing `show()` method tapi route ada di web.php
  - **Fix**: Menambahkan method `show()` yang return view `admin.posts.show`
  - **File**: `app/Http/Controllers/Admin/PostController.php`

#### 3. **Missing View Files** 🔴
- **Admin Organization Members** (4 views):
  - `admin/organizations/members/index.blade.php` - Created ✅
  - `admin/organizations/members/create.blade.php` - Created ✅
  - `admin/organizations/members/edit.blade.php` - Created ✅
  - `admin/organizations/members/show.blade.php` - Created ✅

- **Admin Organization Show**:
  - `admin/organizations/show.blade.php` - Created ✅

- **Admin Posts Show**:
  - `admin/posts/show.blade.php` - Created ✅

- **Admin PPDB Create**:
  - `admin/ppdb/create.blade.php` - Created ✅

### 🔄 Public-Admin Synchronization Fixes

#### 4. **PPDB Gender Value Mismatch** ⚠️ CRITICAL
- **Problem**: Form public gunakan value `L` dan `P`, tapi validation PPDBRequest harapkan `male`/`female`
- **Impact**: **FORM PPDB SELALU GAGAL** - Validasi gagal untuk semua submission
- **Fix**: Ubah value di `pages/ppdb/form.blade.php` dari `L/P` ke `male/female`
- **Files**: 
  - `resources/views/pages/ppdb/form.blade.php`
  - `resources/views/admin/ppdb/create.blade.php`

#### 5. **Missing Field Sanitization: `parent_phone`** 🟡
- **PPDB Public Controller**: Field `parent_phone` tidak disanitasi
  - **Fix**: Menambahkan `$validated['parent_phone'] = strip_tags(...)`
  - **File**: `app/Http/Controllers/PPDBController.php`

- **PPDB Admin Controller**: Field `parent_phone` tidak disanitasi di `store()` dan `update()`
  - **Fix**: Menambahkan sanitization untuk `parent_phone` di kedua method
  - **File**: `app/Http/Controllers/Admin/PPDBController.php`

- **StudentRegistration Controller**: Field `parent_phone` tidak disanitasi
  - **Fix**: Menambahkan sanitization untuk `parent_phone`
  - **File**: `app/Http/Controllers/StudentRegistrationController.php`

### 📊 Summary of Changes

| Category | Issues Found | Status |
|----------|--------------|--------|
| Route Configuration | 1 | ✅ Fixed |
| Missing Controller Methods | 2 | ✅ Fixed |
| Missing View Files | 7 | ✅ Fixed |
| Data Validation Mismatch | 1 (PPDB Gender) | ✅ Fixed |
| Missing Field Sanitization | 4 (parent_phone) | ✅ Fixed |
| **Total** | **15 bugs** | **✅ All Fixed** |

### 🛡️ Security Improvements

- **Input Sanitization**: Semua field form sekarang tersanitasi dengan `strip_tags()`
- **Validation Consistency**: Gender values konsisten (`male/female`) antara public form dan admin validation
- **CSRF Protection**: Tetap aktif di semua form
- **XSS Prevention**: Strip tags dengan allowed tags list untuk rich text fields

### 📝 Notes for Future Development

1. **Selalu cek konsistensi** antara public form values dengan validation rules
2. **Sanitasi semua input fields** di controller, termasuk field yang terlihat "safe" seperti phone numbers
3. **Test form submission** setelah membuat changes untuk memastikan tidak ada validation mismatch
4. **Gunakan enum/constants** untuk values seperti gender agar konsisten di seluruh aplikasi

---

## 📝 Version 1.16 Updates & Fixes

### 🎓 New Features
- **Student Registration System** - Complete account registration workflow
  - Modern hero section with animations and floating cards
  - Comprehensive registration form with validation
  - Admin dashboard for managing registrations
  - Automatic user account creation upon approval
  - Role-based access control for "calon siswa" role
  - Statistics and progress tracking
  - Mobile-responsive design with smooth animations

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
- **Asset optimization**: Removed unused Bootstrap CSS files to reduce project size

### 🎨 UI/UX Improvements
- **Student Registration Interface**: Modern, animated landing page
  - Full-screen hero with gradient backgrounds
  - Floating animation cards with statistics
  - Smooth transitions and hover effects
  - Mobile-responsive design
- **Better avatar display**: More reliable avatar loading with fallback
  - Uses `$user->avatar_url` accessor instead of direct `Storage::url()` calls
  - Shows default avatar when user avatar is missing
- **Enhanced error messages**: More user-friendly error feedback
- **Improved accessibility**: Better alt tags and semantic HTML

### 🏗️ Architecture Enhancements
- **Student Registration Architecture**: Complete MVC implementation
  - `StudentRegistration` model with relationships and scopes
  - `StudentRegistrationController` with CRUD operations
  - `StudentRegistrationRequest` for validation
  - Factory for testing
  - Comprehensive unit tests (15 test cases)
- **Better role system consistency**: Hybrid approach supporting both legacy and Spatie systems
- **Added sync functions**: `syncLegacyRolesToSpatie()` for migration support
- **Improved error logging**: More comprehensive error tracking across systems
- **Backward compatibility maintained**: Still supports legacy role/ability tables

### 🧪 Testing
- **Student Registration Tests**: Complete unit test coverage
  - Model creation and validation tests
  - Relationship and scope tests
  - Approval/rejection workflow tests
  - Accessor and helper method tests
  - Total: 495 test cases passing

## 📸 Screenshots

### Desktop
![Contact Page]
![Password Verification]
![Student Registration]

### Mobile
![Contact Mobile]
![Password Mobile]
![Student Registration Mobile]

## 🔐 Security Features

- **Two-Factor Authentication** - Optional 2FA for enhanced security
- **Rate Limiting** - Protection against brute force attacks
- **IP Change Detection** - Monitors for suspicious location changes
- **Audit Logging** - Comprehensive security event tracking
- **Sensitive Data Encryption** - Phone numbers and addresses encrypted at rest
- **Role-Based Access Control** - Fine-grained permission system
- **Student Registration Validation** - NIK, email, and phone validation

## 🚀 Deployment

### Docker (Recommended)

```bash
# Development
make build-dev && make dev

# Production
make build && make up

# Production with resource limits
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d
```

### Local Development
```bash
php artisan serve
```

### Production Deployment (Manual)
1. Set environment variables appropriately
2. Run migrations: `php artisan migrate --force`
3. Build assets: `npm run build`
4. Configure web server to point to `/public` directory
5. Start queue worker: `php artisan queue:work --daemon`

### Production Deployment (Docker)
1. Copy `.env.example` to `.env` and configure production values
2. Build image: `docker compose build app`
3. Start services: `docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d`
4. Run migrations: `make fresh` or set `AUTO_MIGRATE=true`

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/AmazingFeature`
3. Commit changes: `git commit -m 'Add some AmazingFeature'`
4. Push to the branch: `git push origin feature/AmazingFeature`
5. Open a Pull Request

## 📧 Contact

Built for Madrasah Aliyah Nusantara - Modern Islamic Education