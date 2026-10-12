<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="/images/brand/sch-icon.png">

        @php($seo = data_get($page ?? null, 'props.seo'))
        @if ($seo)
            <title>{{ $seo['title'] }}</title>
            <meta data-inertia="description" name="description" content="{{ $seo['description'] }}">
            <meta data-inertia="robots" name="robots" content="{{ $seo['robots'] }}">
            <link data-inertia="canonical" rel="canonical" href="{{ $seo['canonical'] }}">
            <meta data-inertia="og:title" property="og:title" content="{{ $seo['ogTitle'] }}">
            <meta data-inertia="og:description" property="og:description" content="{{ $seo['ogDescription'] }}">
            <meta data-inertia="og:url" property="og:url" content="{{ $seo['canonical'] }}">
            <meta data-inertia="og:type" property="og:type" content="{{ $seo['ogType'] }}">
            @if ($seo['ogImage'])
                <meta data-inertia="og:image" property="og:image" content="{{ $seo['ogImage'] }}">
            @endif
            <meta data-inertia="twitter:card" name="twitter:card" content="{{ $seo['twitterCard'] }}">
            <meta data-inertia="twitter:title" name="twitter:title" content="{{ $seo['ogTitle'] }}">
            <meta data-inertia="twitter:description" name="twitter:description" content="{{ $seo['ogDescription'] }}">
            @if ($seo['ogImage'])
                <meta data-inertia="twitter:image" name="twitter:image" content="{{ $seo['ogImage'] }}">
            @endif
            @if ($seo['verification'])
                <meta data-inertia="google-site-verification" name="google-site-verification" content="{{ $seo['verification'] }}">
            @endif
            @foreach ($seo['structuredData'] as $schema)
                <script data-inertia="schema-{{ $loop->index }}" type="application/ld+json">{!! json_encode($schema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
            @endforeach
        @elseif (request()->is('admin*'))
            <meta name="robots" content="noindex, nofollow">
        @endif

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @inertiaHead
    </head>
    <body>
        @inertia
    </body>
</html>
