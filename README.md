# the-intels

A small PHP + HTML content-management starter: an admin login with session auth, a JSON article API, and a media upload endpoint, all in flat files you can drop onto any PHP host.

## The pieces

**Article API** (JSON, CORS-enabled):
- `get_articles.php` — list articles from the database
- `create_article.php` / `update_article.php` / `delete_article.php` — the CRUD endpoints

**Auth:**
- `login.php` / `logout.php` — admin credentials against `config.php`
- `check_session.php` — session check, returns `{"logged_in": true|false}` for the frontend

**Frontend:**
- `index.html` — reader-facing page
- `admin_profile.html` — admin panel for writing and managing articles
- `upload_media.php` — media uploads for article images

## Setup

1. Edit `config.php` with your database credentials and admin user.
2. Serve the folder with any PHP-capable server (Apache, `php -S`).
3. Log in via `login.php` and manage content from `admin_profile.html`.

Endpoints return JSON with `Access-Control-Allow-Origin: *`, so a separate frontend can consume the article API directly.
