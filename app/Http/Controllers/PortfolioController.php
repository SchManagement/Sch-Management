<?php

namespace App\Http\Controllers;

use App\Support\PublicProjectData;
use App\Support\PublicSeo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    public function index(Request $request, PublicProjectData $data, PublicSeo $seo): Response
    {
        $category = $request->query('category');

        $payload = $data->index(is_string($category) ? $category : null);
        abort_if($payload['projects']->currentPage() > $payload['projects']->lastPage(), 404);
        $page = max(1, (int) $payload['projects']->currentPage());
        $filtered = $request->has('category');

        return Inertia::render('Portfolio/Index', [
            ...$payload,
            'seo' => $seo->page('/portfolio'.($page > 1 && ! $filtered ? '?page='.$page : ''),
                'Portfolio Web Development | SCH Management',
                'Lihat project web development dan solusi digital yang dikerjakan SCH Management berdasarkan kebutuhan serta tujuan bisnis yang nyata.',
                ['noindex' => $filtered, 'breadcrumbs' => [['label' => 'Beranda', 'path' => '/'], ['label' => 'Portfolio', 'path' => '/portfolio']]]),
        ]);
    }

    public function show(string $slug, PublicProjectData $data, PublicSeo $seo): Response
    {
        $payload = $data->detail($slug);
        $project = $payload['project'];
        $path = '/portfolio/'.$project['slug'];

        return Inertia::render('Portfolio/Show', [
            ...$payload,
            'seo' => $seo->page($path, "{$project['title']} | Portfolio SCH Management",
                $project['short_description'] ?: "Case study project {$project['title']} dari SCH Management.",
                [...$project, 'main_image' => $project['thumbnail'],
                    'breadcrumbs' => [['label' => 'Beranda', 'path' => '/'], ['label' => 'Portfolio', 'path' => '/portfolio'], ['label' => $project['title'], 'path' => $path]]]),
        ]);
    }
}
