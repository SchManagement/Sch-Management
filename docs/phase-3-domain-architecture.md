# Phase 3 domain architecture

## Rich-content representation

`services.full_description`, `services.business_problem`, `services.solution`,
`articles.content`, and `pages.content` use MariaDB-compatible `LONGTEXT` columns.
They are intended to hold CMS-authored HTML once an editor is introduced in a
later phase. HTML must be sanitized against an explicit allowlist before it is
persisted or rendered publicly. This phase does not install an editor or render
rich HTML.

Repeatable structured values such as service features, deliverables, process
steps, audiences, and homepage section configuration use JSON columns with
Eloquent array casts. Homepage JSON contains only section-specific presentation
configuration and references; it must not duplicate complete service, project,
or article records.

## Settings boundaries

The `settings` table stores non-secret company, site, SEO, contact, social, and
analytics configuration. SMTP passwords, database credentials, reCAPTCHA secret
keys, API secrets, and other credentials remain environment-managed secrets and
must never be stored in this table.
