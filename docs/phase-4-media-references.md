# Phase 4 media-reference strategy

Phase 4 keeps editorial image fields (`image`, `thumbnail`, `cover_image`, `photo`, and `og_image`) as nullable string references, matching the existing Phase 3 schema. A reference may contain a Laravel Storage path recorded by the media domain or an absolute URL that the administrator has verified.

The CMS does not assume that every reference has a `media.id`, so existing content remains portable and no fragile foreign-key dependency is introduced. The Media Library currently lists and filters existing metadata only. Uploading, file deletion, image processing, and a media picker are intentionally deferred until they can be implemented together with MIME validation, generated filenames, Storage lifecycle handling, and safe reference checks.

Before public rendering, later phases must resolve local paths through the configured disk, validate external URL policy, emit meaningful alt text, and avoid rendering missing assets. Rich HTML stored in service, article, or page content must be sanitized before output.
