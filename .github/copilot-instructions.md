# Copilot instructions — DemoProject

Purpose
- Provide focused, actionable guidance to code assistants working on this PHP monolith.

Big picture
- This is a small procedural PHP application (no framework). Public-facing pages live at repository root (e.g. `index.php`, `search.php`, `cart.php`).
- `Admin/` contains the admin UI and paired form handlers (see pattern below). Front-end libraries are vendored under `Admin/bower_components/`.
- `model/` contains core utilities: `connect.php` (PDO DB connection), `config.php` (site constants), `mail.php` (mail helper), `session.php` (session bootstrap).
- `Database/` contains one-off scripts to create schema/data (e.g. `create_table.php`, `create_data.php`).
- `vendor/` holds Composer dependencies (PHPMailer expected by `model/mail.php`).

Setup & common workflows
- Install PHP dependencies: run `composer install` (project `composer.json` requires `phpmailer/phpmailer`).
- Create DB/tables: run the scripts in `Database/` or import your own SQL. `model/connect.php` uses PDO and currently contains hard-coded credentials.
- Run a dev server locally:

  php -S localhost:8000 -t .

- Ensure `uploads/` and `logs/` directories are writable by the web server.
- Mail behavior: by default `model/config.php` sets `MAIL_TRANSPORT` to `log` for dev. To send real mail, set `MAIL_TRANSPORT` to `smtp` and configure SMTP_* env vars or update `model/config.php`.

Project-specific conventions
- Procedural pages + paired processors: UI pages and their processors are split. Example: `Admin/product-add.php` (form) and `Admin/productadd-back.php` (form handler).
- DB access uses PDO in `model/connect.php`. Expect associative arrays from fetch operations.
- Session config is centralized in `model/session.php` (cookie params, lifetime). Include/require this early in pages that rely on sessions.
- Mail helper `model/mail.php` prefers PHPMailer via Composer when `MAIL_TRANSPORT === 'smtp'`, otherwise falls back to `mail()` or logs to `logs/email.log`.

Integration points and notable files
- PHPMailer / SMTP: add credentials via environment variables (`SMTP_HOST`, `SMTP_USER`, `SMTP_PASS`, `SMTP_PORT`, `SMTP_SECURE`) or edit `model/config.php` (uses `getenv()` where present).
- Database schema helpers: `Database/create_table.php` and `Database/create_data.php`.
- Admin UI and JS: `Admin/` and `js/mylishop.js` (client-side behaviors).
- Email fallback log: `logs/email.log` (path configured by `model/config.php`).

Debugging notes
- DB connection errors currently call `die()` in `model/connect.php` — run pages directly or check PHP/Apache error log to see details.
- Mail failures are written to `logs/email.log` by `model/mail.php` when PHPMailer fails or is not installed.

Quick examples
- Enable SMTP sending:
  - `composer install`
  - set `MAIL_TRANSPORT` to `smtp` in `model/config.php` and provide `SMTP_*` env vars

- Run local server (quick dev run):
  - `php -S localhost:8000 -t .`

Important caveats
- Secrets: DB creds are currently in `model/connect.php` (hard-coded). Do not commit sensitive production credentials; prefer environment variables.
- There are no automated tests or CI configured in this repo — changes should be tested manually in a dev environment.

If anything above is unclear or you want more detail (e.g., a mapping of routes to handlers, or a short onboarding script), tell me which area to expand.
