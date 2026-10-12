# Phase 10 — Inquiry configuration

The public Contact and Request Project forms share the existing leads table and submission pipeline. No new migration is required.

## Required before accepting real inquiries

1. Create Google reCAPTCHA **v2 Checkbox** keys for the production hostname.
2. Set RECAPTCHA_SITE_KEY and RECAPTCHA_SECRET_KEY in the deployment environment. Only the site key is sent to the browser. The forms remain visibly unavailable without both keys; the server always fails closed.
3. Set APP_URL to the canonical application host. Server verification checks Google's returned hostname against this host.
4. Configure Laravel's real mail transport (MAIL_MAILER=smtp or another delivery provider), host/port/credentials, MAIL_FROM_ADDRESS, and MAIL_FROM_NAME. MAIL_TIMEOUT defaults to 10 seconds. The default log mailer is useful for local development but **does not deliver external email**.
5. Optionally set **Admin → Company Information → Email penerima permintaan project**. If blank, notifications target hilmanalfiqri01@gmail.com. The recipient setting is private and never included in publicSite.
6. After deployment, run php artisan config:cache so env-backed settings are loaded through configuration.

Do not put the reCAPTCHA secret, SMTP password, or visitor tokens in CMS settings or frontend code.

## Pipeline

Both POST routes use dedicated FormRequests, a shared reCAPTCHA verifier, and a shared Lead submission service. Validation and CAPTCHA verification occur before any database write. A honeypot, a shared 5-per-10-minute IP rate limit, and a two-minute email/message/source deduplication window provide additional protection.

The Lead is saved with status new before mail is attempted. A mail failure retains the Lead, records a failure timestamp and sanitized exception class, and logs only the Lead ID and exception class. Visitors see a receipt confirmation after successful database storage, even if mail transport fails. Admin Leads remains the recovery path.

Automated tests should fake Google's siteverify HTTP response and the mail transport; they must not call Google or send real SMTP messages. Google's response token is single-use, so the checkbox must be reset after failed submissions.
