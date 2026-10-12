<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TechnologyRequest;
use App\Models\Technology;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TechnologyController extends Controller
{
    public function index(Request $request): Response
    {
        $items = Technology::query()
            ->when($request->string('search')->toString(), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->when($request->filled('active'), fn ($query) => $query->where('is_active', $request->boolean('active')))
            ->ordered()->paginate(15)->withQueryString()
            ->through(fn (Technology $item) => [
                'id' => $item->id, 'name' => $item->name, 'slug' => $item->slug,
                'website_url' => $item->website_url, 'is_active' => $item->is_active, 'sort_order' => $item->sort_order,
                '_links' => ['edit' => route('admin.technologies.edit', $item), 'delete' => route('admin.technologies.destroy', $item)],
            ]);

        return Inertia::render('Admin/Cms/Index', [
            'resource' => ['title' => 'Teknologi', 'description' => 'Kelola teknologi yang benar-benar menjadi kapabilitas SCH Management.', 'singular' => 'teknologi', 'createLabel' => 'Tambah Teknologi', 'createUrl' => route('admin.technologies.create')],
            'items' => $items,
            'columns' => [
                ['key' => 'name', 'label' => 'Nama', 'primary' => true], ['key' => 'slug', 'label' => 'Slug'],
                ['key' => 'website_url', 'label' => 'Website'], ['key' => 'is_active', 'label' => 'Aktif', 'type' => 'boolean'],
                ['key' => 'sort_order', 'label' => 'Urutan', 'type' => 'number'],
            ],
            'filters' => ['search' => $request->string('search')->toString(), 'searchPlaceholder' => 'Cari teknologi…', 'selects' => [[
                'name' => 'active', 'label' => 'Status', 'value' => $request->input('active', ''),
                'options' => [['value' => '1', 'label' => 'Aktif'], ['value' => '0', 'label' => 'Nonaktif']],
            ]]],
        ]);
    }

    public function create(): Response { return $this->form(); }

    public function store(TechnologyRequest $request): RedirectResponse
    {
        Technology::create($request->validated());
        return redirect()->route('admin.technologies.index')->with('success', 'Teknologi berhasil ditambahkan.');
    }

    public function edit(Technology $technology): Response { return $this->form($technology); }

    public function update(TechnologyRequest $request, Technology $technology): RedirectResponse
    {
        $technology->update($request->validated());
        return redirect()->route('admin.technologies.index')->with('success', 'Teknologi berhasil diperbarui.');
    }

    public function destroy(Technology $technology): RedirectResponse
    {
        $technology->delete();
        return redirect()->route('admin.technologies.index')->with('success', 'Teknologi berhasil dihapus.');
    }

    private function form(?Technology $technology = null): Response
    {
        return Inertia::render('Admin/Cms/Form', [
            'formConfig' => [
                'title' => $technology ? 'Edit Teknologi' : 'Tambah Teknologi', 'description' => 'Simpan referensi teknologi secara ringkas dan terurut.',
                'action' => $technology ? route('admin.technologies.update', $technology) : route('admin.technologies.store'),
                'method' => $technology ? 'put' : 'post', 'cancelUrl' => route('admin.technologies.index'),
                'submitLabel' => $technology ? 'Simpan Perubahan' : 'Tambah Teknologi', 'slugSource' => 'name',
            ],
            'initial' => $technology?->toArray() ?? ['name' => '', 'slug' => '', 'icon' => '', 'website_url' => '', 'sort_order' => 0, 'is_active' => true],
            'sections' => [['title' => 'Detail teknologi', 'fields' => [
                ['name' => 'name', 'label' => 'Nama', 'type' => 'text', 'required' => true],
                ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'required' => true],
                ['name' => 'icon', 'label' => 'Nama/referensi ikon', 'type' => 'text'],
                ['name' => 'website_url', 'label' => 'Website resmi', 'type' => 'url'],
                ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number', 'required' => true, 'min' => 0],
                ['name' => 'is_active', 'label' => 'Teknologi aktif', 'type' => 'checkbox'],
            ]]],
        ]);
    }
}
