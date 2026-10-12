# Optional analytics consent

The public SEO payload exposes `analyticsId` only in production when the public `analytics.ga_measurement_id` setting contains a valid GA4 ID. An empty or invalid ID leaves optional analytics disabled. In that state the site renders no consent banner, preference dialog, footer settings control, or GA4 script.

With a valid ID, the first visit shows a compact consent banner. The versioned first-party `localStorage` entry `sch-cookie-consent-v1` stores only `{ version: 1, essential: true, analytics: boolean }`. GA4 is neither requested nor initialized before Analytics is accepted. Rejecting keeps GA4 disabled. A saved acceptance initializes GA4 on later visits without showing the banner. The footer **Pengaturan Cookie** control reopens preferences; withdrawing consent sets GA4's `ga-disable-G-…` flag and stops page-view tracking. Existing GA script elements may remain in the document after withdrawal, but the GA disable flag prevents further measurement.

Essential Laravel behavior continues regardless of the analytics preference. Contact and Request Project load reCAPTCHA separately as required abuse protection; rejecting Analytics does not gate CSRF, session behavior, form validation, or reCAPTCHA.

The consent UI does not generate legal claims or a Cookie Policy. Review the published Privacy Policy and Terms with the site owner before production use. If either page is unpublished, its route retains the existing 404 behavior.

Browser smoke: `node tests/Smoke/style-b-cookie-browser.mjs` with Laravel at `127.0.0.1:3113` and Chrome CDP at `127.0.0.1:9334`. The enabled-state test substitutes a dummy GA ID in the public Inertia response and verifies banner, rejection, persistence, preferences, acceptance, and withdrawal without changing database settings.
