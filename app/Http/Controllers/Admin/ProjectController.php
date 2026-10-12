<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CategoryType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\Service;
use App\Models\Technology;
use App\Support\Admin\CmsForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(Request $request): Response
    {
        $projects = Project::query()
            ->with(['category:id,name', 'service:id,name'])
            ->when($request->string('search')->toString(), function ($query, $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('client', 'like', "%{$search}%");
                });
            })
            ->when($request->string('status')->toString(), fn ($query, $status) => $query->where('status', $status))
            ->when($request->integer('category_id'), fn ($query, $category) => $query->where('category_id', $category))
            ->ordered()
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Project $project) => [
                'id' => $project->id,
                'title' => $project->title,
                'client' => $project->client,
                'category' => $project->category?->name,
                'status' => $project->status->value,
                'featured' => $project->featured,
                'project_date' => $project->project_date?->toDateString(),
                '_links' => [
                    'edit' => route('admin.projects.edit', $project),
                    'delete' => route('admin.projects.destroy', $project),
                ],
            ]);

        return Inertia::render('Admin/Cms/Index', [
            'resource' => [
                'title' => 'Project',
                'description' => 'Kelola studi kasus dan informasi project tanpa membuat klaim yang belum terverifikasi.',
                'singular' => 'project',
                'createLabel' => 'Tambah Project',
                'createUrl' => route('admin.projects.create'),
            ],
            'items' => $projects,
            'columns' => [
                ['key' => 'title', 'label' => 'Project', 'primary' => true],
                ['key' => 'client', 'label' => 'Klien'],
                ['key' => 'category', 'label' => 'Kategori'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'status'],
                ['key' => 'featured', 'label' => 'Unggulan', 'type' => 'boolean'],
                ['key' => 'project_date', 'label' => 'Tanggal', 'type' => 'date'],
            ],
            'filters' => [
                'search' => $request->string('search')->toString(),
                'searchPlaceholder' => 'Cari judul atau klien...',
                'selects' => [
                    ['name' => 'status', 'label' => 'Status', 'value' => $request->string('status')->toString(), 'options' => $this->statusOptions()],
                    ['name' => 'category_id', 'label' => 'Kategori', 'value' => $request->string('category_id')->toString(), 'options' => $this->categoryOptions()],
                ],
            ],
        ]);
    }

    public function create(): Response
    {
        return $this->form();
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $project = Project::create($request->payload());
            $project->technologies()->sync($request->validated('technology_ids', []));
        });

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil ditambahkan.');
    }

    public function edit(Project $project): Response
    {
        $project->load('technologies:id');

        return $this->form($project);
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        DB::transaction(function () use ($request, $project): void {
            $project->update($request->payload());
            $project->technologies()->sync($request->validated('technology_ids', []));
        });

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil diarsipkan.');
    }

    private function form(?Project $project = null): Response
    {
        $record = $project?->toArray() ?? [
            'title' => '', 'slug' => '', 'client' => '', 'industry' => '', 'category_id' => '', 'service_id' => '',
            'project_date' => '', 'short_description' => '', 'overview' => '', 'challenge' => '', 'solution' => '',
            'results' => '', 'thumbnail' => '', 'project_url' => '', 'featured' => false, 'status' => 'draft',
            'sort_order' => 0, 'published_at' => '', 'seo_title' => '', 'meta_description' => '', 'canonical_url' => '',
            'og_title' => '', 'og_description' => '', 'og_image' => '', 'primary_target_keyword' => '',
            'secondary_keyword_notes' => '', 'is_indexable' => true,
        ];
        $record['status'] = $project?->status->value ?? 'draft';
        $record['project_date'] = $project?->project_date?->toDateString() ?? '';
        $record['published_at'] = $project?->published_at?->format('Y-m-d\TH:i') ?? '';
        $record['technology_ids'] = $project?->technologies->pluck('id')->all() ?? [];

        return Inertia::render('Admin/Cms/Form', [
            'formConfig' => [
                'title' => $project ? 'Edit Project' : 'Tambah Project',
                'description' => 'Catat informasi project yang sah dan siapkan struktur studi kasusnya.',
                'action' => $project ? route('admin.projects.update', $project) : route('admin.projects.store'),
                'method' => $project ? 'put' : 'post',
                'cancelUrl' => route('admin.projects.index'),
                'submitLabel' => $project ? 'Simpan Perubahan' : 'Tambah Project',
                'slugSource' => 'title',
            ],
            'initial' => $record,
            'sections' => [
                ['title' => 'Informasi utama', 'fields' => [
                    ['name' => 'title', 'label' => 'Judul project', 'type' => 'text', 'required' => true],
                    ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'required' => true],
                    ['name' => 'client', 'label' => 'Klien', 'type' => 'text'],
                    ['name' => 'industry', 'label' => 'Industri', 'type' => 'text'],
                    ['name' => 'category_id', 'label' => 'Kategori project', 'type' => 'select', 'options' => $this->categoryOptions()],
                    ['name' => 'service_id', 'label' => 'Layanan terkait', 'type' => 'select', 'options' => $this->serviceOptions()],
                    ['name' => 'project_date', 'label' => 'Tanggal project', 'type' => 'date'],
                    ['name' => 'project_url', 'label' => 'URL project', 'type' => 'url'],
                    ['name' => 'thumbnail', 'label' => 'Referensi thumbnail', 'type' => 'text', 'help' => 'Gunakan path media atau URL yang sah.'],
                    ['name' => 'technology_ids', 'label' => 'Teknologi', 'type' => 'multiselect', 'options' => $this->technologyOptions(), 'span' => 2],
                    ['name' => 'short_description', 'label' => 'Deskripsi singkat', 'type' => 'textarea', 'rows' => 3, 'span' => 2],
                ]],
                ['title' => 'Studi kasus', 'fields' => [
                    ['name' => 'overview', 'label' => 'Ikhtisar', 'type' => 'textarea', 'rows' => 6, 'span' => 2],
                    ['name' => 'challenge', 'label' => 'Tantangan', 'type' => 'textarea', 'rows' => 6],
                    ['name' => 'solution', 'label' => 'Solusi', 'type' => 'textarea', 'rows' => 6],
                    ['name' => 'results', 'label' => 'Hasil', 'type' => 'textarea', 'rows' => 6, 'span' => 2],
                ]],
                ['title' => 'Publikasi', 'fields' => [
                    ...CmsForm::publicationFields(),
                    ['name' => 'featured', 'label' => 'Tandai sebagai project unggulan', 'type' => 'checkbox'],
                    ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number', 'required' => true, 'min' => 0],
                ]],
                ['title' => 'SEO', 'fields' => CmsForm::seoFields()],
            ],
        ]);
    }

    private function statusOptions(): array
    {
        return [['value' => 'draft', 'label' => 'Draft'], ['value' => 'published', 'label' => 'Dipublikasikan']];
    }

    private function categoryOptions(): array
    {
        return Category::ofType(CategoryType::Project)->ordered()->get(['id', 'name'])
            ->map(fn (Category $category) => ['value' => $category->id, 'label' => $category->name])->all();
    }

    private function serviceOptions(): array
    {
        return Service::ordered()->get(['id', 'name'])
            ->map(fn (Service $service) => ['value' => $service->id, 'label' => $service->name])->all();
    }

    private function technologyOptions(): array
    {
        return Technology::ordered()->get(['id', 'name'])
            ->map(fn (Technology $technology) => ['value' => $technology->id, 'label' => $technology->name])->all();
    }
}
