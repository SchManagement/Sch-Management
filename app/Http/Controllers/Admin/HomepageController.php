<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomepageRequest;
use App\Models\HomepageSection;
use App\Models\Article;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class HomepageController extends Controller
{
    public function edit(): Response
    {
        $existing = HomepageSection::query()->get()->keyBy('section_key');
        $labels = [
            'hero' => 'Hero', 'about' => 'Tentang SCH', 'services' => 'Layanan', 'why_sch' => 'Mengapa SCH',
            'portfolio' => 'Portfolio', 'process' => 'Proses', 'technology' => 'Teknologi',
            'insights' => 'Insight', 'final_cta' => 'CTA Penutup',
        ];

        $sections = collect(HomepageSection::KEYS)->map(function (string $key, int $index) use ($existing, $labels): array {
            $section = $existing->get($key);

            return [
                'section_key' => $key,
                'label' => $labels[$key],
                'heading' => $section?->heading ?? '',
                'content_json' => $section?->content ? json_encode($section->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '',
                'is_enabled' => $section?->is_enabled ?? true,
                'sort_order' => $section?->sort_order ?? (($index + 1) * 10),
            ];
        });

        return Inertia::render('Admin/Homepage/Edit', [
            'sections' => $sections,
            'referenceOptions' => [
                'services' => Service::ordered()->get(['id', 'name', 'status'])->map(fn (Service $item) => ['id' => $item->id, 'label' => $item->name, 'status' => $item->status->value]),
                'portfolio' => Project::ordered()->get(['id', 'title', 'status'])->map(fn (Project $item) => ['id' => $item->id, 'label' => $item->title, 'status' => $item->status->value]),
                'insights' => Article::query()->latest()->get(['id', 'title', 'status'])->map(fn (Article $item) => ['id' => $item->id, 'label' => $item->title, 'status' => $item->status->value]),
            ],
        ]);
    }

    public function update(HomepageRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            foreach ($request->sections() as $section) {
                HomepageSection::updateOrCreate(['section_key' => $section['section_key']], $section);
            }
        });

        return back()->with('success', 'Pengaturan homepage berhasil disimpan.');
    }
}
