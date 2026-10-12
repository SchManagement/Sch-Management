<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use App\Support\PublicSeo;
use Illuminate\Http\Response;
use XMLWriter;

class SeoInfrastructureController extends Controller
{
    public function sitemap(PublicSeo $seo): Response
    {
        $urls = [];

        foreach (['home', 'services.index', 'portfolio.index', 'insights.index', 'contact.show'] as $route) {
            $urls[route($route)] = null;
        }
        $about = Page::query()->published()->where('key', 'about')->first(['is_indexable', 'canonical_url', 'updated_at']);
        if ((! $about || $about->is_indexable)
            && (! $about || $seo->canonical('/about', $about->canonical_url) === $seo->absolute('/about'))) {
            $urls[route('about')] = $about?->updated_at?->toAtomString();
        }

        foreach ([
            [Service::class, 'services.show', '/services/'],
            [Project::class, 'portfolio.show', '/portfolio/'],
            [Article::class, 'insights.show', '/insights/'],
        ] as [$model, $route, $prefix]) {
            $model::query()->published()->indexable()
                ->select(['id', 'slug', 'canonical_url', 'updated_at'])
                ->orderBy('id')->chunkById(200, function ($records) use (&$urls, $seo, $route, $prefix): void {
                    foreach ($records as $record) {
                        $path = $prefix.$record->slug;
                        if ($seo->canonical($path, $record->canonical_url) === $seo->absolute($path)) {
                            $urls[route($route, ['slug' => $record->slug])] = $record->updated_at?->toAtomString();
                        }
                    }
                });
        }

        foreach ([
            'privacy-policy' => ['privacy-policy', '/privacy-policy'],
            'terms' => ['terms', '/terms'],
        ] as $key => [$route, $path]) {
            $page = Page::query()->published()->indexable()->where('key', $key)
                ->first(['id', 'canonical_url', 'updated_at']);
            if ($page && $seo->canonical($path, $page->canonical_url) === $seo->absolute($path)) {
                $urls[route($route)] = $page->updated_at?->toAtomString();
            }
        }

        return $this->xml($urls);
    }

    /** @param array<string, ?string> $urls */
    private function xml(array $urls): Response
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('urlset');
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        foreach ($urls as $url => $modified) {
            $xml->startElement('url');
            $xml->writeElement('loc', $url);
            if ($modified) $xml->writeElement('lastmod', $modified);
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endDocument();

        return response($xml->outputMemory(), 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(PublicSeo $seo): Response
    {
        $sitemap = rtrim((string) config('app.url'), '/').route('sitemap', absolute: false);
        $body = $seo->indexingEnabled()
            ? "User-agent: *\nAllow: /\nDisallow: /admin/\n\nSitemap: {$sitemap}\n"
            : "User-agent: *\nDisallow: /\n";

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
