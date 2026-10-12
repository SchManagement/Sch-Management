# Phase 8 — Portfolio public architecture

## Data loading

- `/portfolio` loads published, current, non-deleted projects with category and active technology relations eagerly loaded.
- The list is paginated on the server and accepts the shareable `category` query parameter.
- Filter options contain only active `project` categories that have at least one publicly available project.
- `/portfolio/{slug}` uses the same publication scope and returns a real HTTP 404 for unavailable records.

## Case-study rendering

Long-form `overview`, `challenge`, `solution`, and `results` values are rendered as escaped plain text through `PlainTextContent`. No unsanitized CMS HTML is rendered.

The schema does not contain a dedicated project-development-process field. Phase 8 therefore omits that section instead of adding a migration or manufacturing content.

## Gallery and media

Project images retain the relation's `sort_order`. A marked `is_cover` image is preferred as the hero fallback when no project thumbnail exists. Gallery media uses normalized public media URLs, meaningful CMS alt text or contextual fallback text, stable aspect ratios, and lazy loading below the hero.

The gallery is intentionally static and dependency-free. Advanced dialog/lightbox behavior is deferred until it has a clear content need.

## External project URLs

Only absolute HTTP or HTTPS destinations with a host and without embedded credentials are exposed. Protocol-relative URLs, non-web schemes, and same-host admin destinations are rejected. External project links open in a new tab with `noopener noreferrer` and visible/screen-reader context.

## Related projects

Candidates must be public and share a category, service, or active technology with the current project. A bounded candidate set is scored by service, category, technology overlap, and featured state; no more than three projects are returned.
