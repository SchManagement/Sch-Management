<?php

namespace App\Http\Controllers;

use App\Support\AboutData;
use App\Support\PublicSeo;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function __invoke(AboutData $about, PublicSeo $seo): Response
    {
        $data = $about->get();

        return Inertia::render('About', [
            ...$data,
            'seo' => $seo->page('/about',
                'Tentang SCH Management | Web Development & IT Solutions',
                'Mengenal SCH Management dan pendekatan kami dalam membangun website, sistem digital, serta solusi IT yang terarah dan mudah dikembangkan.',
                [...($data['aboutPage'] ?? []), 'breadcrumbs' => [
                    ['label' => 'Beranda', 'path' => '/'], ['label' => 'Tentang', 'path' => '/about'],
                ]]),
        ]);
    }
}
