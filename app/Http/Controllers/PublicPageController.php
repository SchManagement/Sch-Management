<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Support\PublicMedia;
use App\Support\PublicSeo;
use Inertia\Inertia;
use Inertia\Response;

class PublicPageController extends Controller
{
    public function privacy(PublicSeo $seo): Response
    {
        return $this->show('privacy-policy', '/privacy-policy', 'Kebijakan Privasi', $seo);
    }

    public function terms(PublicSeo $seo): Response
    {
        return $this->show('terms', '/terms', 'Ketentuan', $seo);
    }

    private function show(string $key, string $path, string $label, PublicSeo $seo): Response
    {
        $page = Page::query()->published()->where('key', $key)->firstOrFail();

        return Inertia::render('ContentPage', [
            'contentPage' => ['title' => $page->title, 'content' => $page->content],
            'seo' => $seo->page($path, "{$page->title} | SCH Management",
                "{$label} SCH Management.", [
                    'seo_title' => $page->seo_title,
                    'meta_description' => $page->meta_description,
                    'canonical_url' => $page->canonical_url,
                    'og_title' => $page->og_title,
                    'og_description' => $page->og_description,
                    'og_image' => PublicMedia::url($page->og_image),
                    'is_indexable' => $page->is_indexable,
                    'breadcrumbs' => [['label' => 'Beranda', 'path' => '/'], ['label' => $label, 'path' => $path]],
                ]),
        ]);
    }
}
