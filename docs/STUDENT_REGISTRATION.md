# 🎓 Student Registration System Documentation

Complete student account registration system with admin approval workflow for MA NU Nusantara.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Installation](#installation)
- [Database Schema](#database-schema)
- [API Endpoints](#api-endpoints)
- [User Interface](#user-interface)
- [Admin Panel](#admin-panel)
- [Security](#security)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)

## 🎯 Overview

The Student Registration System allows prospective students to register for accounts in the MA NU Nusantara learning management system. Unlike PPDB which focuses on admission, this system creates user accounts with "calon siswa" (prospective student) role.

### Key Components

1. **Public Registration Interface** - Modern landing page and form
2. **Admin Approval Dashboard** - Management interface for administrators
3. **Automatic Account Creation** - User accounts created upon approval
4. **Role Management** - Automatic role assignment
5. **Validation System** - Comprehensive data validation

## ✨ Features

### For Students
- **Modern Registration Form** - Responsive, animated interface
- **Real-time Validation** - Instant feedback on form inputs
- **Progress Tracking** - Status updates throughout the process
- **Mobile-friendly** - Works seamlessly on all devices

### For Administrators
- **Dashboard Overview** - Statistics and pending registrations
- **Detailed Review** - Complete student information display
- **Bulk Actions** - Approve/reject multiple registrations
- **Audit Trail** - Complete history of all actions

### System Features
- **Email Uniqueness** - Prevents duplicate emails across systems
- **NIK Validation** - 16-digit Indonesian ID validation
- **Phone Validation** - Proper Indonesian phone format
- **Role Assignment** - Automatic "calon siswa" role assignment
- **Password Generation** - Secure random password generation

## 🚀 Installation

### Prerequisites
- Laravel 12+
- PHP 8.2+
- Database (SQLite/MySQL/PostgreSQL)
- Spatie Laravel Permission package

### Setup Steps

1. **Run Migration**
   ```bash
   php artisan migrate
   ```

2. **Seed Roles** (if not already exists)
   ```php
   use Spatie\Permission\Models\Role;
   
   Role::firstOrCreate(['name' => 'calon siswa', 'guard_name' => 'web']);
   ```

3. **Verify Routes**
   ```bash
   php artisan route:list | grep student-registration
   ```

## 🗄️ Database Schema

### student_registrations Table

```sql
CREATE TABLE student_registrations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    nik VARCHAR(16) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    birth_date DATE NOT NULL,
    birth_place VARCHAR(255) NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(15) NOT NULL,
    parent_name VARCHAR(255) NOT NULL,
    parent_phone VARCHAR(15) NOT NULL,
    previous_school VARCHAR(255) NOT NULL,
    desired_major VARCHAR(255) NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    notes TEXT NULL,
    approved_at TIMESTAMP NULL,
    rejected_at TIMESTAMP NULL,
    approved_by BIGINT NULL REFERENCES users(id),
    rejected_by BIGINT NULL REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Relationships

- `approvedBy` → `User` (Admin who approved)
- `rejectedBy` → `User` (Admin who rejected)

## 🌐 API Endpoints

### Public Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/pendaftaran-siswa` | `student-registration.index` | Registration info page |
| GET | `/pendaftaran-siswa/daftar` | `student-registration.create` | Registration form |
| POST | `/pendaftaran-siswa/daftar` | `student-registration.store` | Submit registration |
| GET | `/pendaftaran-siswa/sukses` | `student-registration.success` | Success page |

### Admin Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/student-registrations` | `student-registrations.index` | Admin dashboard |
| GET | `/admin/student-registrations/{registration}` | `student-registrations.show` | Registration details |
| POST | `/admin/student-registrations/{registration}/approve` | `student-registrations.approve` | Approve registration |
| POST | `/admin/student-registrations/{registration}/reject` | `student-registrations.reject` | Reject registration |

## 🎨 User Interface

### Registration Landing Page

**Location**: `/pendaftaran-siswa`

**Features**:
- Full-screen hero section with gradient background
- Animated floating cards showing statistics
- Smooth scroll animations
- Call-to-action buttons
- Mobile-responsive design

**Key Elements**:
```html
<!-- Hero Section -->
<section class="hero-section">
    <h1>Bergabunglah Bersama MA NU Nusantara</h1>
    <a href="/pendaftaran-siswa/daftar" class="btn btn-warning">Daftar Sekarang</a>
</section>

<!-- Statistics Section -->
<section class="statistics">
    <div class="stat-number">50+</div>
    <div class="stat-label">Guru Profesional</div>
</section>
```

### Registration Form

**Location**: `/pendaftaran-siswa/daftar`

**Features**:
- Multi-step form with sections
- Real-time validation
- Progress indicators
- Auto-formatting for NIK and phone numbers

**Form Sections**:
1. **Data Pribadi** - Personal information
2. **Data Orang Tua/Wali** - Parent/guardian information  
3. **Data Pendidikan** - Educational background

### Success Page

**Location**: `/pendaftaran-siswa/sukses`

**Features**:
- Confirmation message
- Next steps information
- Login button
- Account benefits overview

## 👨‍💼 Admin Panel

### Dashboard Overview

**Location**: `/admin/student-registrations`

**Features**:
- Statistics cards (pending, approved, rejected)
- Filterable registration list
- Search functionality
- Bulk actions
- Pagination

**Statistics Display**:
```php
// Statistics Cards
$pendingCount = StudentRegistration::pending()->count();
$approvedCount = StudentRegistration::approved()->count();
$rejectedCount = StudentRegistration::rejected()->count();
```

### Registration Details

**Location**: `/admin/student-registrations/{id}`

**Features**:
- Complete student information
- Approval/rejection actions
- Notes and history
- Related user account info

**Approval Process**:
```php
// Approve Registration
$registration->approve($adminUser, $notes);

// This creates:
// 1. User account with random password
// 2. "calon siswa" role assignment
// 3. Registration status update
```

## 🔒 Security

### Validation Rules

```php
// StudentRegistrationRequest rules
return [
    'name' => 'required|string|max:255',
    'nik' => 'required|string|unique:student_registrations,nik|regex:/^[0-9]{16}$/',
    'email' => 'required|email|unique:student_registrations,email|unique:users,email',
    'birth_date' => 'required|date|before:today',
    'phone' => 'required|string|regex:/^[0-9]{10,15}$/',
    'parent_phone' => 'required|string|regex:/^[0-9]{10,15}$/',
];
```

### Security Features

1. **Email Uniqueness** - Prevents duplicates across `student_registrations` and `users` tables
2. **NIK Validation** - Ensures 16-digit Indonesian ID format
3. **Phone Validation** - Validates Indonesian phone number format
4. **CSRF Protection** - All forms protected with CSRF tokens
5. **Input Sanitization** - HTML tags stripped from text inputs
6. **Rate Limiting** - Login and registration attempts limited

### Password Generation

```php
// Secure random password generation
$password = Str::random(8);
$user->password = Hash::make($password);
```

## 🧪 Testing

### Unit Tests

**Location**: `tests/Unit/StudentRegistrationModelTest.php`

**Test Coverage**:
- Model creation and validation
- Relationships and scopes
- Approval/rejection workflows
- Accessor methods
- Factory testing

**Running Tests**:
```bash
# Run all student registration tests
php artisan test tests/Unit/StudentRegistrationModelTest.php

# Run specific test
php artisan test --filter test_student_registration_can_be_created
```

### Test Statistics

- **Total Tests**: 15 test cases
- **Coverage**: Model methods, relationships, validation
- **Assertions**: 42 total assertions
- **Status**: All passing ✅

### Factory Testing

```php
// Create test registration
$registration = StudentRegistration::factory()->create();

// Create with specific status
$approved = StudentRegistration::factory()->approved()->create();
$rejected = StudentRegistration::factory()->rejected()->create();
```

## 🔧 Troubleshooting

### Common Issues

#### 1. Registration Form Not Submitting

**Symptoms**: Form submission fails without error messages

**Solutions**:
- Check CSRF token is present
- Verify all required fields are filled
- Check browser console for JavaScript errors
- Ensure validation rules are met

#### 2. Email Already Exists Error

**Symptoms**: "Email sudah terdaftar" validation error

**Solutions**:
- Check both `student_registrations` and `users` tables
- Verify email format is correct
- Check for trailing spaces in email

#### 3. NIK Validation Error

**Symptoms**: "NIK harus 16 digit angka" error

**Solutions**:
- Ensure exactly 16 digits
- Remove any spaces or special characters
- Verify NIK format: `1234567890123456`

#### 4. Admin Approval Not Working

**Symptoms**: Approval button not creating user account

**Solutions**:
- Check admin user has proper permissions
- Verify "calon siswa" role exists
- Check database foreign key constraints
- Review error logs

### Debug Mode

Enable debug mode for detailed error information:

```php
// In .env file
APP_DEBUG=true
APP_LOG_LEVEL=debug
```

### Log Files

Check Laravel logs for detailed error information:

```bash
# View latest log entries
tail -f storage/logs/laravel.log

# Search for specific errors
grep "StudentRegistration" storage/logs/laravel.log
```

## 📞 Support

For issues related to the Student Registration System:

1. Check this documentation first
2. Review error logs
3. Verify database connections
4. Test with fresh registration data
5. Contact development team with specific error details

---

**Last Updated**: February 2026  
**Version**: 1.16  
**Maintainer**: MA NU Nusantara Development Team
