# Changelog

## [Unreleased] - 2025-12-02

### Added
- **Docker Environment:** Created `Dockerfile` (PHP 5.6, Apache) and `docker-compose.yml` (MySQL 5.7).
- **Documentation:** Created `docs/PROJECT_CONTEXT.md` and `docs/CHANGELOG.md`.
- **Git:** Initialized Git repository and added `.gitignore`.

### Changed
- **Port Configuration:** Changed web container port to `8090` to avoid conflicts.
- **Database Config:** Updated `includes/config/_config.inc.php` to use environment variables for DB credentials and set `SiteUrl` to `localhost` (fixing double port issue).
- **Debug Mode:** Disabled debug output by setting `$DebugLevel = 0` in config and forcing return in `debuginfo.php`. Re-enabled standard PHP error reporting in `index.php` for critical errors.
- **HTTPS/Redirects:** Renamed `htaccess_off` to `.htaccess` and commented out HTTPS redirect rules. Disabled PHP-level HTTPS checks.
- **Database Content:** Replaced hardcoded `https://diti.by` and `http://diti.by` URLs with `http://localhost:8090` in `brand_footer`, `banner`, `article`, `registry_value`, and `shipping_info` tables. Fixed `localhost:8090:8090` double port errors in DB.

### Fixed
- **White Screen:** Enabled error reporting to diagnose issues.
- **Missing Assets:** Created `var/log` and `_r` directories.
- **Cyclic Redirects:** Resolved by disabling HTTPS enforcement.
- **Navigation Fix:** Manually patched `includes/php/classes/base/controls/navi.php` to include `global $navi, $RootPath;` in constructor, fixing variable scope issues.
- **Cleanup:** Removed temporary fix files (`CProductsPage_FIXED.php`, `database_FIXED.php`).
