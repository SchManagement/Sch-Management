<?php

namespace App\Http\Controllers;

use App\Support\PublicArticleData;
use App\Support\PublicSeo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InsightController extends Controller
{
    public function index(Request $request, PublicArticleData $data, PublicSeo $seo): Response
    {
        $category = $request->query('category');
        $search = $request->query('search');

        $payload = $data->index(
            is_string($category) ? $category : null,
            is_string($search) ? $search : null,
        );
        abort_if($payload['articles']->currentPage() > $payload['articles']->lastPage(), 404);
        $page = max(1, (int) $payload['articles']->currentPage());
        $filtered = $request->has('category') || $request->has('search');

        return Inertia::render('Insights/Index', [
            ...$payload,
            'seo' => $seo->page('/insights'.($page > 1 && ! $filtered ? '?page='.$page : ''),
                'Insight Web Development & Teknologi | SCH Management',
                'Insight praktis seputar web development, teknologi, UI/UX, website maintenance, dan digitalisasi bisnis dari SCH Management.',
                ['noindex' => $filtered, 'breadcrumbs' => [['label' => 'Beranda', 'path' => '/'], ['label' => 'Insight', 'path' => '/insights']]]),
        ]);
    }

    public function show(string $slug, PublicArticleData $data, PublicSeo $seo): Response
    {
        $payload = $data->detail($slug);
        $article = $payload['article'];
        $path = '/insights/'.$article['slug'];
        $schema = [
            '@context' => 'https://schema.org', '@type' => 'BlogPosting',
            'headline' => $article['title'],
            'mainEntityOfPage' => $seo->absolute($path),
            'publisher' => ['@type' => 'Organization', 'name' => 'SCH Management'],
        ];
        if ($article['excerpt']) $schema['description'] = strip_tags($article['excerpt']);
        if ($image = $seo->image($article['og_image'] ?: $article['cover_image'])) $schema['image'] = $image;
        if ($article['published_at']) $schema['datePublished'] = $article['published_at'];
        if ($article['updated_at']) $schema['dateModified'] = $article['updated_at'];
        if ($article['author']) $schema['author'] = ['@type' => 'Person', 'name' => $article['author']['name']];

        return Inertia::render('Insights/Show', [
            ...$payload,
            'seo' => $seo->page($path, "{$article['title']} | SCH Management",
                $article['excerpt'] ?: "Baca insight {$article['title']} dari SCH Management.",
                [...$article, 'main_image' => $article['cover_image'], 'og_type' => 'article', 'schema' => [$schema],
                    'breadcrumbs' => [['label' => 'Beranda', 'path' => '/'], ['label' => 'Insight', 'path' => '/insights'], ['label' => $article['title'], 'path' => $path]]]),
        ]);
    }
}
