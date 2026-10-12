# SCH Management — production deployment and first launch

This guide is for the existing Laravel 12 / Inertia / Vue application. Use the final hostname and real credentials in the deployment environment; never commit them. Technical SEO does not guarantee indexing or ranking. Also see [production SEO](production-seo.md), [inquiry configuration](phase-10-inquiry.md), and [security hardening](phase-15-hardening.md).

## Before deployment

1. Take and verify a restorable MariaDB backup before any migration or update. Keep a rollback copy of the currently deployed code and assets. Do not use `migrate:fresh`, `migrate:refresh`, `migrate:reset`, or `db:wipe`.
2. Confirm PHP 8.2+, required PHP extensions, Composer, Node/npm, MariaDB connectivity, a TLS certificate, and the final canonical hostname. Point the web-server document root **only** at `public/`; block direct access to `.env`, `.git`, private storage, logs, backups, and vendor metadata.
3. Give the web-server account write access to `storage/` and `bootstrap/cache/` using appropriate ownership/group permissions, not world-writable `777`.

## Environment checklist

| Area | Configure and verify |
| --- | --- |
| Application | `APP_NAME="SCH Management"`, `APP_ENV=production`, `APP_DEBUG=false`, final HTTPS `APP_URL`, persistent secret `APP_KEY` (do not regenerate for an existing installation). |
| MariaDB | `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`. Use a least-privilege database account and back up before migrations. |
| Session | Production `SESSION_DRIVER` and storage availability; `SESSION_SECURE_COOKIE=true` behind HTTPS; retain HTTP-only cookies and an appropriate `SESSION_SAME_SITE` (normally `lax`). Set `SESSION_DOMAIN` only if required by the final host plan. |
| reCAPTCHA | `RECAPTCHA_SITE_KEY`, `RECAPTCHA_SECRET_KEY` for **v2 Checkbox**, registered for the final `APP_URL` hostname. Never place the secret in CMS settings or frontend assets. Without both keys, inquiry submission remains unavailable. |
| Mail | Real `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`, and a suitable `MAIL_SCHEME`/TLS transport setting for the selected provider. This repository's Laravel mail configuration reads `MAIL_SCHEME`, not a separate `MAIL_ENCRYPTION` option. Do not leave the `log` mailer for real delivery. |
| SEO/analytics | In Admin → SEO, set the preferred canonical HTTPS origin if it differs from `APP_URL`, review global metadata and real OG image, enable indexing only when ready, configure Google Site Verification, and optionally configure a valid GA4 measurement ID. |
| Inquiry recipient | In Admin → Company Information, set a private recipient address if the approved fallback recipient is not desired. The fallback is `hilmanalfiqri01@gmail.com`. |
| Logging | Use production-appropriate `LOG_CHANNEL` and `LOG_LEVEL`; protect `storage/logs/laravel.log` and review it after launch. |

`.env.example` is intentionally a local-development template with empty placeholders. Do not copy its debug, SQLite, log-mail, or localhost values into production unchanged. The application must be reached over HTTPS, redirect HTTP to HTTPS, and use the same preferred HTTPS host for `APP_URL`, canonicals, and reCAPTCHA. Enable HSTS at the HTTPS web server/proxy only after certificate coverage and redirects are verified; never enable it for localhost. Test a full Content Security Policy in report-only mode before enforcement because Vite/Inertia, reCAPTCHA, optional GA4, and Three.js have distinct requirements. The application already emits a narrow framing CSP and baseline security headers.

## Deployment order

1. Back up the database and current release. If deploying in place, enter maintenance mode only when the new release and migration are ready; plan a way to exit maintenance mode on failure.
2. Upload or switch to the reviewed code release. Run `composer install --no-dev --optimize-autoloader` and `npm ci` in the release environment. Do not run Composer's `setup` script in production; it may generate a key or migrate at the wrong time.
3. Configure production environment values and writable paths. If public media files are deliberately stored on Laravel's public disk, create/verify the public storage link with `php artisan storage:link`; private local storage must remain inaccessible.
4. Run `npm run build`. Keep the generated `public/build/manifest.json` and referenced hashed assets together in the release.
5. Run `php artisan migrate --force` only after reviewing pending migrations and the backup. Run `php artisan config:cache`, `php artisan route:cache`, and `php artisan view:cache` for this repository; all three were verified locally. Restart PHP workers/OPcache as appropriate so the new code and configuration are active.
6. Check `/up` and required asset responses before leaving maintenance mode. Exit maintenance mode with `php artisan up`, then check `/`, major public pages, `/admin/login`, `/sitemap.xml`, `/robots.txt`, and logs. Keep the previous release and backup available until live checks pass.

For an atomic release strategy, build/install in a separate release directory before switching the live symlink, and coordinate migrations with application compatibility. If deployment fails, restore the previous code/assets and database backup as appropriate; do not assume a data-changing migration can be safely reversed.

## First-launch verification

- Check Home at mobile and desktop sizes, mobile navigation, the first-entry loader, Three hero fallback and reduced motion. Confirm Services, Portfolio, Insights, Contact, Request Project, footer, and any genuine published detail/legal pages. Verify draft/future/deleted content remains inaccessible.
- Submit **one authorized real inquiry** after live reCAPTCHA and SMTP are configured; confirm the Lead appears in Admin with source/status, the recipient receives the notification, and no secrets appear in logs or public payloads. Automated fake-mail/fake-Google checks do **not** prove external delivery.
- Check Admin login/authorization and one safe CMS edit. Confirm public settings and SEO changes appear without a stale application cache. This application does not add an aggressive public-settings cache.
- Inspect title, description, HTTPS canonical, robots, OG, JSON-LD, `/sitemap.xml`, and `/robots.txt` on the live host. Verify Google Search Console ownership, submit the sitemap, inspect initial pages, and monitor indexing, Search Performance, and Core Web Vitals. Configure GA4 only if desired and verify events after Inertia navigation.
- Confirm SSL and HTTP→HTTPS redirects, secure session cookies, baseline security headers, absence of public debug output, and that private storage/backups are not web-accessible. Review `storage/logs/laravel.log` after deployment and again after the first live form test.

## Local validation used for this release

`php tests/Smoke/phase11-seo.php` and `php tests/Smoke/phase15-security.php` use transactional QA records and mocked services. `tests/Smoke/phase14-responsive.mjs` and `tests/Smoke/phase15-browser.mjs` use local headless Chrome. They do not substitute for a screen-reader audit, production Lighthouse/Core Web Vitals measurements, penetration testing, live Google reCAPTCHA, live SMTP, or Search Console verification.
