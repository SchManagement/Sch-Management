# Phase 6 homepage architecture

The public homepage is loaded by `HomeController` through `HomepageData`. The support service is responsible for section configuration, publication filtering, selected-record references, relationship loading, and the small public payload sent to Inertia.

## CMS behavior

- Missing `homepage_sections` records use the approved fallback copy in the corresponding Vue section component.
- A stored section with `is_enabled = false` is omitted.
- Stored `sort_order` controls section order, except `hero` remains first whenever enabled so the visual H1 stays first in the document outline.
- `content.selected_ids` references Service, Project, or Article IDs. Full domain records are never copied into homepage JSON.
- Optional `content.limit` values are bounded by the backend.
- CTA URLs are checked in the frontend and fall back to the approved internal paths if their protocol is not allowed.

## Publication and media rules

Services, projects, and articles are queried through their `published()` scopes. Draft records and records with a future `published_at` are not exposed. If one of these collections is empty, its homepage section renders no public markup rather than showing fabricated cards or a database-empty message.

Media fields support absolute HTTP(S) URLs, root-relative paths, or storage-relative paths. Storage-relative paths are exposed as `/storage/{path}`. Protocol-relative media URLs are not used as external URLs.

## Phase boundary

Phase 6 uses static CSS/SVG geometry and restrained component hover transitions. It does not include GSAP, ScrollTrigger, Three.js, public detail pages, or the complete Phase 11 SEO engine. Semantic `data-reveal`, `data-stagger`, `data-hero-visual`, and `data-process-step` hooks are present for later animation phases.
