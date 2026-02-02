# Changelog

All notable changes to this project are documented in this file.

## [1.16.0] - 2026-02-02 — Student Registration System

### Added
- **Complete Student Registration System** - Full account registration workflow with admin approval
  - Modern, animated landing page with hero section and floating cards
  - Comprehensive registration form with real-time validation
  - Admin dashboard for managing student registrations
  - Automatic user account creation upon approval
  - Role-based access control for "calon siswa" role
  - Statistics and progress tracking
  - Mobile-responsive design with smooth animations

### New Components
- `StudentRegistration` model with relationships and scopes
- `StudentRegistrationController` with CRUD operations
- `StudentRegistrationRequest` for comprehensive validation
- `StudentRegistrationFactory` for testing
- Admin views for registration management
- Public views for registration interface

### Database Changes
- New `student_registrations` table with complete schema
- Foreign key relationships to users table
- Status tracking (pending/approved/rejected)
- Audit trail with approved_by/rejected_by fields

### Features
- **Public Interface**:
  - Full-screen hero section with gradient backgrounds
  - Floating animation cards showing school statistics
  - Multi-step registration form with validation
  - Success page with next steps information
  
- **Admin Interface**:
  - Dashboard with statistics cards
  - Filterable and searchable registration list
  - Detailed registration view with approval actions
  - Bulk approval/rejection capabilities
  - Complete audit trail

- **Validation & Security**:
  - NIK 16-digit validation
  - Email uniqueness across registration and user tables
  - Phone number validation for Indonesian format
  - Input sanitization and CSRF protection
  - Rate limiting and security logging

### Testing
- Complete unit test suite for StudentRegistration (15 test cases)
- Model creation and validation tests
- Relationship and scope tests
- Approval/rejection workflow tests
- Total test suite: 495 test cases passing

### Documentation
- Updated README.md with Student Registration System section
- New comprehensive documentation in `docs/STUDENT_REGISTRATION.md`
- Updated version to 1.16.0
- Added installation and troubleshooting guides

### UI/UX Improvements
- Modern gradient hero section with animations
- Floating cards with smooth transitions
- Responsive design for all screen sizes
- Enhanced form validation with real-time feedback
- Professional admin dashboard with statistics

## Unreleased — Offline CSS assets and performance optimization

### Added
- All CSS assets (Google Fonts, Bootstrap Icons from npm, Font Awesome) now stored locally for offline capability
- Documentation updated to reflect offline-first CSS approach

### Changed
- Removed unused Bootstrap CSS files (grid, reboot, utilities, RTL versions, source maps) to optimize project size
- Updated all layout files to reference local CSS assets instead of CDN
- Updated to use Bootstrap Icons from npm package (public/css/bootstrap-icons-npm/)
- Updated documentation files (README, INSTALLATION_GUIDE, ARCHITECTURE, API_DOCS, CONTACT_PAGE_README, LIBRARY_SETUP, SYSTEM_FLOW, DEMO_GUIDE, PROJECT_NOTES) with CSS asset information

## 2025-10-27 — Test fixes, security audit, OTP, and UI fixes

### Fixed
- Restore admin edit UIs for Students and Teachers; resolve NOT NULL constraint for `students.nis` so CRUD and tests pass.
- OTP verification: count validation failures as attempts, enforce rate-limiting and account locking at expected thresholds, and emit consistent security log actions (`account_locked`, `otp_success`, etc.).
- Security audit: align repository and views with DB schema (`action` column), compute dashboard statistics via JSON extraction from `data`, and provide recent high-risk events.

### Changed
- CSV export: test relaxed to accept `Content-Type` containing charset (e.g., `text/csv; charset=UTF-8`) while `Content-Disposition` remains exact; CSV generation reworked to produce stable output.

### Tests
- Fixed and aligned feature tests (Pest) for OTP rate-limiting and security audit. Full feature suite passes locally (27 tests, 97 assertions).

### Notes
- Middleware aliases and RBAC checks were consolidated to use class-based middleware registration for predictable behaviour (403 for unauthorized authenticated users, 302 for guests).

If you want a PR created from these changes, tell me and I can prepare a branch summary and suggested PR description.
