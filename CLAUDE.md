# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

CodeToAdventure is a PHP web application for Rivian enthusiasts to share and discover referral codes. It displays random referral codes with links to Rivian's shop, allows user submissions, and includes Twitter automation for marketing.

**Live site**: https://www.codetoadventure.com

## Architecture

**Stack**: PHP 7.2+/8.0+, MySQL, vanilla JavaScript, Tailwind CSS (CDN), TwitterOAuth

**Pattern**: Monolithic PHP with server-side rendering. No MVC framework - pages are self-contained PHP files with shared includes.

### Key Directories

- `/admin/` - Admin dashboard, authentication, code management (login.php, admin.php, edit.php, delete.php)
- `/includes/` - Shared template components (head.php, header.php, footer.php, modal.php)
- `/styles/` - CSS architecture with base/, components/, layout/, pages/ subdirectories
- `/js/` - JavaScript modules (main.js handles forms, themes, API interactions)
- `/twitteroauth/` - Twitter API v2 library (Composer-managed)

### Main Endpoints

| File | Purpose |
|------|---------|
| `index.php` | Home page - displays random referral code |
| `submit.php` | Code submission form UI |
| `store_code.php` | AJAX endpoint for saving submitted codes |
| `get_new_code.php` | AJAX endpoint for fetching random code |
| `api.php` | Public JSON API (/api?path=random, /api?path=codes) |
| `process_tweets.php` | Twitter automation script (batches of 3 codes) |
| `diagnostic.php` | System health check |

### Data Flow

1. User visits `index.php` → random code from database
2. Submit form → POST to `store_code.php` → inserted to `codes` table
3. Code added to `pending_tweets` queue
4. `process_tweets.php` (scheduled) posts batches to Twitter
5. Admin manages codes via `/admin/admin.php`

## Configuration

**Required files not in repo** (create locally):
- `config.php` - Database connection credentials
- `credentials.php` - Twitter API keys and tokens

These are excluded via `.gitignore` for security.

## Development

### Syntax Check
```bash
php -l filename.php
```

### Testing Endpoints
- `/diagnostic.php` - Verify PHP version, extensions, database connectivity
- `/geoip_test.php` - Test IP geolocation
- `?test_twitter` query param on `store_code.php` - Test Twitter API

### CSS Variables

Theme colors defined in `/styles/base/_variables.css`. Dark mode uses `[data-theme="dark"]` selector pattern with localStorage persistence.

### API Rate Limiting

Public API limited to 100 requests per 60 seconds per IP. CORS restricted to codetoadventure.com domain.

## Database Tables

Primary tables: `codes`, `users`, `pending_tweets`, `tweet_stats`, `code_analytics`

All queries use prepared statements with bound parameters.

## Version Numbering

Commits use semantic versioning format: `YYYY.WW.N` (year.week.iteration), e.g., "2025.46.1"
