<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use App\Support\Admin\CmsForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(Request $request): Response
    {
        $services = Service::query()
            ->when($request->string('search')->toString(), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->when($request->string('status')->toString(), fn ($query, $status) => $query->where('status', $status))
            ->ordered()
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Service $service) => [
                'id' => $service->id,
                'name' => $service->name,
                'slug' => $service->slug,
                'status' => $service->status->value,
                'sort_order' => $service->sort_order,
                'published_at' => $service->published_at?->toISOString(),
                '_links' => [
                    'edit' => route('admin.services.edit', $service),
                    'delete' => route('admin.services.destroy', $service),
                ],
            ]);

        return Inertia::render('Admin/Cms/Index', [
            'resource' => [
                'title' => 'Layanan',
                'description' => 'Kelola penawaran layanan dan kesiapan publikasinya.',
                'singular' => 'layanan',
                'createLabel' => 'Tambah Layanan',
                'createUrl' => route('admin.services.create'),
            ],
            'items' => $services,
            'columns' => [
                ['key' => 'name', 'label' => 'Nama', 'primary' => true],
                ['key' => 'slug', 'label' => 'Slug'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'status'],
                ['key' => 'sort_order', 'label' => 'Urutan', 'type' => 'number'],
                ['key' => 'published_at', 'label' => 'Publikasi', 'type' => 'date'],
            ],
            'filters' => [
                'search' => $request->string('search')->toString(),
                'searchPlaceholder' => 'Cari nama layanan…',
                'selects' => [[
                    'name' => 'status',
                    'label' => 'Status',
                    'value' => $request->string('status')->toString(),
                    'options' => [['value' => 'draft', 'label' => 'Draft'], ['value' => 'published', 'label' => 'Dipublikasikan']],
                ]],
            ],
        ]);
    }

    public function create(): Response
    {
        return $this->form();
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        Service::create($request->payload());

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service): Response
    {
        return $this->form($service);
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->payload());

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diarsipkan.');
    }

    private function form(?Service $service = null): Response
    {
        $record = $service?->toArray() ?? [
            'name' => '', 'slug' => '', 'icon' => '', 'short_description' => '', 'full_description' => '',
            'business_problem' => '', 'solution' => '', 'image' => '', 'status' => 'draft', 'sort_order' => 0,
            'published_at' => '', 'seo_title' => '', 'meta_description' => '', 'canonical_url' => '',
            'og_title' => '', 'og_description' => '', 'og_image' => '', 'primary_target_keyword' => '',
            'secondary_keyword_notes' => '', 'is_indexable' => true,
        ];

        foreach (['features', 'deliverables', 'development_process', 'suitable_for'] as $field) {
            $record[$field.'_input'] = implode("\n", $service?->{$field} ?? []);
            unset($record[$field]);
        }

        $record['status'] = $service?->status->value ?? 'draft';
        $record['published_at'] = $service?->published_at?->format('Y-m-d\TH:i') ?? '';

        return Inertia::render('Admin/Cms/Form', [
            'formConfig' => [
                'title' => $service ? 'Edit Layanan' : 'Tambah Layanan',
                'description' => 'Susun informasi layanan yang jelas, lengkap, dan siap dikelola untuk publikasi.',
                'action' => $service ? route('admin.services.update', $service) : route('admin.services.store'),
                'method' => $service ? 'put' : 'post',
                'cancelUrl' => route('admin.services.index'),
                'submitLabel' => $service ? 'Simpan Perubahan' : 'Tambah Layanan',
                'slugSource' => 'name',
            ],
            'initial' => $record,
            'sections' => [
                ['title' => 'Informasi utama', 'fields' => [
                    ['name' => 'name', 'label' => 'Nama layanan', 'type' => 'text', 'required' => true],
                    ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'required' => true, 'help' => 'Digunakan pada URL publik dan dapat disunting.'],
                    ['name' => 'icon', 'label' => 'Nama/referensi ikon', 'type' => 'text'],
                    ['name' => 'image', 'label' => 'Referensi gambar', 'type' => 'text', 'help' => 'Gunakan path media atau URL yang sah.'],
                    ['name' => 'short_description', 'label' => 'Deskripsi singkat', 'type' => 'textarea', 'rows' => 3, 'span' => 2],
                    ['name' => 'full_description', 'label' => 'Deskripsi lengkap', 'type' => 'textarea', 'rows' => 8, 'span' => 2],
                    ['name' => 'business_problem', 'label' => 'Masalah bisnis', 'type' => 'textarea', 'rows' => 5],
                    ['name' => 'solution', 'label' => 'Solusi', 'type' => 'textarea', 'rows' => 5],
                ]],
                ['title' => 'Struktur layanan', 'description' => 'Masukkan satu item per baris.', 'fields' => [
                    ['name' => 'features_input', 'label' => 'Fitur', 'type' => 'textarea', 'rows' => 5],
                    ['name' => 'deliverables_input', 'label' => 'Deliverables', 'type' => 'textarea', 'rows' => 5],
                    ['name' => 'development_process_input', 'label' => 'Proses pengembangan', 'type' => 'textarea', 'rows' => 5],
                    ['name' => 'suitable_for_input', 'label' => 'Cocok untuk', 'type' => 'textarea', 'rows' => 5],
                ]],
                ['title' => 'Publikasi', 'fields' => [
                    ...CmsForm::publicationFields(),
                    ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number', 'required' => true, 'min' => 0],
                ]],
                ['title' => 'SEO', 'description' => 'Gunakan nilai yang spesifik dan tidak berlebihan.', 'fields' => CmsForm::seoFields()],
            ],
        ]);
    }
}
