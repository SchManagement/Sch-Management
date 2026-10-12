# Phase 15 accessibility and security deployment notes

This pass targets practical WCAG 2.2 AA behavior, not certification or a penetration test. Recheck keyboard navigation and a real screen reader before release.

## Production configuration

- Set `APP_ENV=production` and `APP_DEBUG=false`; never publish `.env`. Serve the Laravel `public/` directory as the web root, not the repository root. Deny web access to `.git`, logs, private storage, database dumps, and Composer files at the web server.
- Serve the site over HTTPS with a final `APP_URL`, set `SESSION_SECURE_COOKIE=true`, retain HTTP-only and appropriate SameSite session cookies, and use a production session store. Regenerate application/configuration caches after environment changes.
- Enable HSTS only at the HTTPS web server/proxy after validating certificate coverage, redirects, and subdomains. Do not enable HSTS for localhost or an unverified staging hostname.
- The application emits `nosniff`, `SAMEORIGIN`, `strict-origin-when-cross-origin`, restricted camera/microphone/geolocation permissions, and a narrow `frame-ancestors 'self'` CSP. This CSP clause prevents other sites from framing SCH pages; it does not block SCH from embedding Google's reCAPTCHA iframe.
- A complete script/style/connect/image CSP is deliberately deferred until deployment can test Vite/Inertia, reCAPTCHA, conditional GA4, and the homepage Three.js chunk. Start with a report-only policy in staging, enumerate required sources, then enforce a nonce/hash-based policy. Avoid a cosmetic `unsafe-inline`-heavy policy.
- Keep reCAPTCHA secret and mail credentials only in environment/server configuration. Configure trusted proxies and allowed hosts at the deployment edge so rate limiting, HTTPS detection, and canonical origins remain trustworthy.
- The private Laravel `local` disk does not expose signed file-serving or upload routes. Public media references must resolve to explicitly public `/storage/` files or safe HTTP(S) URLs. Do not use the private disk as a public upload mechanism.

## Verification and remaining checks

Run `php tests/Smoke/phase15-security.php`, `php tests/Smoke/phase11-seo.php`, `npm run build`, `npm audit`, `composer audit --locked`, and `git diff --check`. The Phase 15 smoke uses a database transaction, fake Google HTTP responses, and fake mail; it does not prove real Google verification or SMTP delivery.

Before production launch, use an actual keyboard and screen reader on representative public/admin workflows, inspect WCAG contrast in the deployed browser, test the real reCAPTCHA checkbox and configured SMTP, scan the deployed headers/CSP with an external tool, and review web-server exposure. Dependency audits describe currently known advisories, not absence of exploitable defects.
