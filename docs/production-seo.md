# SCH Management — production SEO setup

Technical SEO is configured through **Admin → SEO** and the normal Laravel environment configuration. It is a foundation for discovery, not a guarantee of Google ranking.

## Deployment checklist

1. Set `APP_ENV=production` and the final HTTPS `APP_URL` to the preferred, single canonical host. Configure the web server to redirect HTTP and any alternate `www`/non-`www` host to it.
2. In Admin → SEO, set Canonical Base URL to that same origin if it differs from `APP_URL`. Leave it blank otherwise. Review the global title, description, and real OG image. Enable indexing only when the production site is ready.
3. Configure a genuine Google Site Verification token in Admin → SEO, then verify the site/domain in Google Search Console. No token is emitted when this field is blank.
4. Check `/robots.txt` and open `/sitemap.xml`. Submit the latter in Search Console. The sitemap includes published/indexable canonical content and excludes drafts, future content, deleted content, noindex records, and query URLs.
5. Inspect the homepage URL in Search Console. Request indexing for important initial pages, then monitor **Page Indexing**, **Search Performance**, and **Core Web Vitals**. Improve content based on actual queries and user needs.
6. Optionally configure a real GA4 `G-...` measurement ID in Admin → SEO. GA loads only in `production` and only on public pages. It is initialized once per loaded browser document and sends page-view events on Inertia navigation; no ID means no GA request.

## Content and canonical rules

Entity-specific SEO fields take priority over contextual titles/descriptions, followed by global defaults and safe SCH fallbacks. A configured title suffix is appended only if it is not already present in the selected title. `primary_target_keyword` and `secondary_keyword_notes` are editorial guidance only; they never become a meta keywords tag.

Canonical URLs are absolute and based on the configured canonical origin. Invalid or unsafe entity canonical overrides fall back to the generated route URL. Filter and search variants of `/portfolio` and `/insights` use `noindex, follow` and canonicalize to the unfiltered index. Unfiltered pagination page 2+ is self-canonical so its distinct items are not falsely attributed to page 1. Query URLs are never included in the sitemap. `/request-project` is intentionally `noindex, follow`; `/contact` remains indexable in production.

Local/staging environments always emit `noindex` metadata, `robots.txt` with `Disallow: /`, and an empty sitemap, regardless of the Admin indexing toggle. Production with indexing disabled behaves the same. Admin and error responses carry noindex protection. The current `/privacy-policy` and `/terms` routes return 404 until genuine published Page records are supplied; no legal text is generated automatically.

Service, article, organization, website, and breadcrumb structured data use only real available values. A project receives breadcrumb markup, not a speculative Product schema. There is no fake SearchAction, address, pricing, rating, review, or social profile. Article/Service/Page rich content remains escaped plain text in the public UI.

Ranking also depends on useful content, search competition, domain reputation and backlinks, search intent, performance, site age, and ongoing improvements. A sitemap and metadata do not guarantee indexing or ranking.
