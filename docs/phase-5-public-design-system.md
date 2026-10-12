# Phase 5 public design system

The SCH Management public UI uses a light-first, corporate technology direction. White and light surfaces remain dominant; midnight/navy establish hierarchy, while blue and cyan are controlled accents.

## Core tokens

Tokens live in `resources/css/app.css` through Tailwind CSS 4 `@theme` variables and a small set of root-level layout/motion variables.

- Brand: `sch-midnight`, `sch-navy`, `sch-dark-blue`, `sch-blue`, `sch-electric`, `sch-cyan`, and `sch-soft-blue`.
- Surfaces and text: `sch-surface`, `sch-subtle`, `sch-ink`, `sch-muted`, and `sch-border`.
- Shape: `rounded-sch-sm` through `rounded-sch-xl`.
- Elevation: `shadow-sch-sm`, `shadow-sch-md`, and `shadow-sch-blue`.
- Layout: `.public-container`, `.public-container-wide`, and `.public-section`.
- Typography: `.public-display`, `.public-heading`, `.public-subheading`, `.public-body-lg`, and `.public-prose-measure`.

The type stack intentionally uses one modern system-oriented sans-serif stack and does not introduce an external font request.

## Layout and navigation

`PublicLayout.vue` owns the skip link, public feedback region, navbar, main landmark, route-focus behavior, and footer. Page-specific business content does not belong in the layout.

The navbar supports two modes:

- `headerMode="solid"` for normal content pages.
- `headerMode="overlay"` for the future dark homepage hero. It starts transparent and becomes a white, bordered surface after a lightweight scroll threshold.

Header and footer navigation prefer active CMS `NavigationItem` records. Safe specification-level fallback links are used when the CMS is empty. Public settings are restricted to records marked `is_public`; contact and social blocks are omitted when no legitimate value exists.

## Interaction and accessibility

- Interactive controls target at least 44–48 CSS pixels.
- Focus indicators use the accessible primary blue token and are never removed.
- Mobile navigation uses a modal drawer, focus containment, Escape dismissal, backdrop dismissal, body scroll lock, and focus return.
- Motion uses short CSS transform/opacity transitions only and inherits the global reduced-motion override.
- Form primitives expose visible labels, help text, inline errors, invalid state, semantic input types, and disabled/loading states.
- SVG icons share a consistent outline style; emoji are not used as interface icons.

## Phase boundary

This phase does not contain final homepage sections, final content pages, GSAP, ScrollTrigger, Three.js, contact submission, or fabricated business content. Public route targets beyond `/` are the approved future URL architecture and will receive their final pages in the corresponding later phases.
