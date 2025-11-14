# Changelog

All notable changes to this project are documented in this file.

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
