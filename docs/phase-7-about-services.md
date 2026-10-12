# Phase 7 About and Services architecture

## Public data loading

- `/about` is handled by `AboutController` and `AboutData`.
- `/services` and `/services/{slug}` are handled by the public `ServiceController` and `PublicServiceData`.
- Fixed public pages use safe approved fallback copy when no published CMS page exists.
- Service detail lookup starts from the `published()` scope and uses `firstOrFail()`. Draft, future-scheduled, soft-deleted, and missing services therefore return HTTP 404.
- Related projects use the Project `published()` scope, eager-load only the category and active technologies required by the card, and are limited to two records.

## About content and founder behavior

The About story prefers the published Page record with `key = about`. Its `content` field is treated as plain text. Vision, mission, values, and development philosophy currently use neutral positioning copy because the existing schema has no dedicated structured fields for them.

Founder information is read only from public settings. The section is omitted unless `company.founder_name` is configured. No fallback identity, team member, or biography is fabricated.

## Rich content decision

Page and service LONGTEXT fields are rendered through `PlainTextContent.vue`. The component splits blank-line-separated paragraphs and outputs escaped Vue text interpolation. HTML is not interpreted and `v-html` is not used.

Service JSON fields are normalized by the backend into a bounded list of title/description items before reaching Vue. Malformed, unsupported, or empty values are omitted rather than causing the page to fail.

There is no dedicated FAQ field in the current Service schema. Phase 7 does not overload another field or add a migration solely to force FAQ content. Dedicated FAQ CMS architecture remains deferred until a legitimate data model is approved.

## Phase boundary

Portfolio URLs used by related project cards retain the approved future architecture, but Portfolio pages remain Phase 8. Request Project and Contact targets remain reserved for Phase 10. Full canonical, Open Graph, JSON-LD, and breadcrumb structured data remain Phase 11.
