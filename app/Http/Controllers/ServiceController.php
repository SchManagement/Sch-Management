<?php

namespace App\Http\Controllers;

use App\Support\PublicServiceData;
use App\Support\PublicSeo;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(PublicServiceData $services, PublicSeo $seo): Response
    {
        return Inertia::render('Services/Index', [
            ...$services->index(),
            'seo' => $seo->page('/services', 'Jasa Pembuatan Website & IT Solutions | SCH Management',
                'Layanan pembuatan website profesional, company profile, landing page, web application, maintenance, dan solusi IT untuk kebutuhan bisnis.',
                ['breadcrumbs' => [['label' => 'Beranda', 'path' => '/'], ['label' => 'Layanan', 'path' => '/services']]]),
        ]);
    }

    public function show(string $slug, PublicServiceData $services, PublicSeo $seo): Response
    {
        $data = $services->detail($slug);
        $service = $data['service'];
        $path = '/services/'.$service['slug'];
        $schema = [
            '@context' => 'https://schema.org', '@type' => 'Service',
            'name' => $service['name'], 'url' => $seo->absolute($path),
            'provider' => ['@type' => 'Organization', 'name' => 'SCH Management'],
        ];
        if ($description = $service['short_description'] ?: $service['full_description']) {
            $schema['description'] = strip_tags($description);
        }

        return Inertia::render('Services/Show', [
            ...$data,
            'seo' => $seo->page($path, "{$service['name']} | SCH Management",
                $service['short_description'] ?: "Pelajari layanan {$service['name']} dari SCH Management untuk mendukung kebutuhan digital bisnis.",
                [...$service, 'main_image' => $service['image'], 'schema' => [$schema],
                    'breadcrumbs' => [['label' => 'Beranda', 'path' => '/'], ['label' => 'Layanan', 'path' => '/services'], ['label' => $service['name'], 'path' => $path]]]),
        ]);
    }
}
