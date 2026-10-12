# Phase 9 — Insights public architecture

## Index queries

- `/insights` uses the existing `published()` scope and selects only card fields.
- Category filters contain only active `article` categories that have published/current articles.
- Search is normalized to a maximum of 100 characters and searches only `title` and `excerpt`.
- Search/category state uses query parameters and is preserved by pagination.
- Nine records are returned per page with category and author eager-loaded.

## Featured behavior

A featured composition is shown only when a real published article has `featured = true`, on page one, and when no category/search filter is active. The newest flagged article is chosen deterministically by publication, creation, and ID order. It is excluded from the default article grid to avoid duplication. No article flag is changed automatically, and the latest unflagged article is not presented as featured.

## Detail and content safety

Article detail uses the same publication protection and exposes only an author's name. Email, administrator status, IDs, and authentication attributes are never serialized.

Article `content` is treated as escaped plain text and rendered through `PlainTextContent` with paragraph preservation. No unsanitized `v-html` or rich-text dependency is used.

Because plain text has no reliable heading structure, a generated table of contents is intentionally deferred rather than introducing fragile parsing.

## Related articles

Up to three published/current articles from the same active article category are returned, ordered by recency. The current article is excluded. If there is no active category or no related content, the section is omitted.
