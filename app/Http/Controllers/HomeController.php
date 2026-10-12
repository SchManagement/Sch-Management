<?php

namespace App\Http\Controllers;

use App\Support\HomepageData;
use App\Support\PublicSeo;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(HomepageData $homepage, PublicSeo $seo): Response
    {
        return Inertia::render('Home', [
            ...$homepage->get(),
            'seo' => $seo->page('/',
                'Jasa Pembuatan Website Profesional | SCH Management',
                'SCH Management menyediakan jasa pembuatan website profesional, web application, IT solutions, maintenance, dan solusi digital untuk bisnis.',
                ['schema' => $seo->homeSchema()]),
        ]);
    }
}
