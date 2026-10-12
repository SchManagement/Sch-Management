<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(Request $request): Response
    {
        $items = Category::query()
            ->when($request->string('search')->toString(), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->when($request->string('type')->toString(), fn ($query, $type) => $query->where('type', $type))
            ->ordered()->paginate(15)->withQueryString()
            ->through(fn (Category $item) => [
                'id' => $item->id, 'name' => $item->name, 'slug' => $item->slug, 'type' => $item->type->value,
                'is_active' => $item->is_active, 'sort_order' => $item->sort_order,
                '_links' => ['edit' => route('admin.categories.edit', $item), 'delete' => route('admin.categories.destroy', $item)],
            ]);

        return Inertia::render('Admin/Cms/Index', [
            'resource' => ['title' => 'Kategori', 'description' => 'Pisahkan kategori artikel dan project secara konsisten.', 'singular' => 'kategori', 'createLabel' => 'Tambah Kategori', 'createUrl' => route('admin.categories.create')],
            'items' => $items,
            'columns' => [
                ['key' => 'name', 'label' => 'Nama', 'primary' => true], ['key' => 'type', 'label' => 'Tipe', 'type' => 'category'],
                ['key' => 'slug', 'label' => 'Slug'], ['key' => 'is_active', 'label' => 'Aktif', 'type' => 'boolean'],
                ['key' => 'sort_order', 'label' => 'Urutan', 'type' => 'number'],
            ],
            'filters' => ['search' => $request->string('search')->toString(), 'searchPlaceholder' => 'Cari kategori…', 'selects' => [[
                'name' => 'type', 'label' => 'Tipe', 'value' => $request->string('type')->toString(),
                'options' => [['value' => 'article', 'label' => 'Artikel'], ['value' => 'project', 'label' => 'Project']],
            ]]],
        ]);
    }

    public function create(): Response { return $this->form(); }

    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category): Response { return $this->form($category); }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori dihapus. Konten terkait tetap tersimpan tanpa kategori.');
    }

    private function form(?Category $category = null): Response
    {
        $record = $category?->toArray() ?? ['name' => '', 'slug' => '', 'type' => 'article', 'description' => '', 'sort_order' => 0, 'is_active' => true];
        $record['type'] = $category?->type->value ?? 'article';

        return Inertia::render('Admin/Cms/Form', [
            'formConfig' => [
                'title' => $category ? 'Edit Kategori' : 'Tambah Kategori', 'description' => 'Kategori dengan slug sama diperbolehkan hanya jika tipenya berbeda.',
                'action' => $category ? route('admin.categories.update', $category) : route('admin.categories.store'),
                'method' => $category ? 'put' : 'post', 'cancelUrl' => route('admin.categories.index'),
                'submitLabel' => $category ? 'Simpan Perubahan' : 'Tambah Kategori', 'slugSource' => 'name',
            ],
            'initial' => $record,
            'sections' => [['title' => 'Detail kategori', 'fields' => [
                ['name' => 'name', 'label' => 'Nama', 'type' => 'text', 'required' => true],
                ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'required' => true],
                ['name' => 'type', 'label' => 'Tipe', 'type' => 'select', 'required' => true, 'options' => [['value' => 'article', 'label' => 'Artikel'], ['value' => 'project', 'label' => 'Project']]],
                ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number', 'required' => true, 'min' => 0],
                ['name' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea', 'rows' => 4, 'span' => 2],
                ['name' => 'is_active', 'label' => 'Kategori aktif', 'type' => 'checkbox'],
            ]]],
        ]);
    }
}
