<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use App\Support\Admin\CmsForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function index(Request $request): Response
    {
        $items = Page::query()
            ->when($request->string('search')->toString(), fn ($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->when($request->string('status')->toString(), fn ($query, $status) => $query->where('status', $status))
            ->orderBy('title')->paginate(12)->withQueryString()
            ->through(fn (Page $page) => [
                'id' => $page->id, 'title' => $page->title, 'key' => $page->key, 'slug' => $page->slug,
                'status' => $page->status->value, 'is_indexable' => $page->is_indexable,
                'published_at' => $page->published_at?->toISOString(),
                '_links' => ['edit' => route('admin.pages.edit', $page), 'delete' => route('admin.pages.destroy', $page)],
            ]);

        return Inertia::render('Admin/Cms/Index', [
            'resource' => ['title' => 'Halaman', 'description' => 'Kelola halaman umum seperti Tentang, Kebijakan Privasi, dan Ketentuan.', 'singular' => 'halaman', 'createLabel' => 'Tambah Halaman', 'createUrl' => route('admin.pages.create')],
            'items' => $items,
            'columns' => [
                ['key' => 'title', 'label' => 'Judul', 'primary' => true], ['key' => 'key', 'label' => 'Kunci'],
                ['key' => 'slug', 'label' => 'Slug'], ['key' => 'status', 'label' => 'Status', 'type' => 'status'],
                ['key' => 'is_indexable', 'label' => 'Indeks', 'type' => 'boolean'], ['key' => 'published_at', 'label' => 'Publikasi', 'type' => 'date'],
            ],
            'filters' => ['search' => $request->string('search')->toString(), 'searchPlaceholder' => 'Cari judul halaman...', 'selects' => [[
                'name' => 'status', 'label' => 'Status', 'value' => $request->string('status')->toString(),
                'options' => [['value' => 'draft', 'label' => 'Draft'], ['value' => 'published', 'label' => 'Dipublikasikan']],
            ]]],
        ]);
    }

    public function create(): Response { return $this->form(); }

    public function store(PageRequest $request): RedirectResponse
    {
        Page::create($request->validated());
        return redirect()->route('admin.pages.index')->with('success', 'Halaman berhasil ditambahkan.');
    }

    public function edit(Page $page): Response { return $this->form($page); }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $page->update($request->validated());
        return redirect()->route('admin.pages.index')->with('success', 'Halaman berhasil diperbarui.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Halaman berhasil dihapus.');
    }

    private function form(?Page $page = null): Response
    {
        $record = $page?->toArray() ?? [
            'key' => '', 'title' => '', 'slug' => '', 'content' => '', 'status' => 'draft', 'published_at' => '',
            'seo_title' => '', 'meta_description' => '', 'canonical_url' => '', 'og_title' => '', 'og_description' => '',
            'og_image' => '', 'primary_target_keyword' => '', 'secondary_keyword_notes' => '', 'is_indexable' => true,
        ];
        $record['status'] = $page?->status->value ?? 'draft';
        $record['published_at'] = $page?->published_at?->format('Y-m-d\TH:i') ?? '';

        return Inertia::render('Admin/Cms/Form', [
            'formConfig' => [
                'title' => $page ? 'Edit Halaman' : 'Tambah Halaman', 'description' => 'Kelola konten halaman umum tanpa membuat informasi perusahaan yang belum terverifikasi.',
                'action' => $page ? route('admin.pages.update', $page) : route('admin.pages.store'), 'method' => $page ? 'put' : 'post',
                'cancelUrl' => route('admin.pages.index'), 'submitLabel' => $page ? 'Simpan Perubahan' : 'Tambah Halaman', 'slugSource' => 'title',
            ],
            'initial' => $record,
            'sections' => [
                ['title' => 'Konten halaman', 'fields' => [
                    ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true],
                    ['name' => 'key', 'label' => 'Kunci internal', 'type' => 'text', 'required' => true, 'help' => 'Contoh: about, privacy-policy, terms.'],
                    ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'required' => true],
                    ['name' => 'content', 'label' => 'Konten', 'type' => 'textarea', 'rows' => 16, 'span' => 2, 'help' => 'HTML terkontrol dapat disimpan; sanitasi wajib sebelum dirender ke publik.'],
                ]],
                ['title' => 'Publikasi', 'fields' => CmsForm::publicationFields()],
                ['title' => 'SEO', 'fields' => CmsForm::seoFields()],
            ],
        ]);
    }
}
