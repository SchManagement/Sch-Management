<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('code') — SCH Management</title>
    <style>
        :root{color-scheme:dark;font-family:system-ui,-apple-system,"Segoe UI",sans-serif}
        *{box-sizing:border-box}body{margin:0;min-height:100svh;background:#06101f;color:#fff;display:grid;grid-template-rows:auto 1fr}
        a{color:inherit}a:focus-visible{outline:3px solid #00b5ff;outline-offset:4px}
        header,main{width:min(100% - 2.5rem,80rem);margin-inline:auto}header{padding-block:1.5rem;border-bottom:1px solid #ffffff25}
        header a{display:inline-flex;align-items:center;gap:.875rem;text-decoration:none;font-weight:700;letter-spacing:-.02em}header img{width:2.75rem;height:2.75rem;object-fit:contain}
        main{display:grid;align-content:center;grid-template-columns:minmax(0,1fr) auto;gap:3rem;padding-block:5rem}
        .eyebrow{color:#73d8ff;text-transform:uppercase;letter-spacing:.18em;font-size:.75rem;font-weight:700}
        h1{font-size:clamp(5rem,16vw,12rem);letter-spacing:-.08em;line-height:.9;margin:.75rem 0 1.5rem}
        h2{font-size:clamp(1.5rem,3vw,2.5rem);letter-spacing:-.04em;line-height:1.18;margin:0;max-width:21ch}
        p{color:#cbd5e1;line-height:1.7;max-width:55ch;margin:1.25rem 0 2rem}
        .action{display:inline-flex;min-height:3rem;align-items:center;padding:.75rem 1.25rem;background:#006cff;text-decoration:none;font-weight:700}
        .action:hover{background:#008cff}.mark{width:min(22vw,16rem);align-self:center;opacity:.8}
        @media(max-width:640px){main{grid-template-columns:1fr;padding-block:4rem}.mark{display:none}}
    </style>
</head>
<body>
    <header><a href="/"><img src="/images/brand/sch-icon.png" alt=""><span>SCH Management</span></a></header>
    <main>
        <div><span class="eyebrow">SCH Management</span><h1>@yield('code')</h1><h2>@yield('heading')</h2><p>@yield('message')</p><a class="action" href="/">Kembali ke Beranda</a></div>
        <img class="mark" src="/images/brand/sch-icon.png" alt="" aria-hidden="true">
    </main>
</body>
</html>
