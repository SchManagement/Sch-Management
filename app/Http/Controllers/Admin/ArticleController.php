<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CategoryType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Support\Admin\CmsForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    public function index(Request $request): Response
    {
        $articles = Article::query()
            ->with(['category:id,name', 'author:id,name'])
            ->when($request->string('search')->toString(), fn ($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->when($request->string('status')->toString(), fn ($query, $status) => $query->where('status', $status))
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Article $article) => [
                'id' => $article->id,
                'title' => $article->title,
                'category' => $article->category?->name,
                'author' => $article->author?->name,
                'status' => $article->status->value,
                'featured' => $article->featured,
                'published_at' => $article->published_at?->toISOString(),
                '_links' => ['edit' => route('admin.articles.edit', $article), 'delete' => route('admin.articles.destroy', $article)],
            ]);

        return Inertia::render('Admin/Cms/Index', [
            'resource' => ['title' => 'Artikel', 'description' => 'Kelola insight dan status publikasinya.', 'singular' => 'artikel', 'createLabel' => 'Tulis Artikel', 'createUrl' => route('admin.articles.create')],
            'items' => $articles,
            'columns' => [
                ['key' => 'title', 'label' => 'Judul', 'primary' => true], ['key' => 'category', 'label' => 'Kategori'],
                ['key' => 'author', 'label' => 'Penulis'], ['key' => 'status', 'label' => 'Status', 'type' => 'status'],
                ['key' => 'featured', 'label' => 'Unggulan', 'type' => 'boolean'], ['key' => 'published_at', 'label' => 'Publikasi', 'type' => 'date'],
            ],
            'filters' => ['search' => $request->string('search')->toString(), 'searchPlaceholder' => 'Cari judul artikel...', 'selects' => [[
                'name' => 'status', 'label' => 'Status', 'value' => $request->string('status')->toString(),
                'options' => [['value' => 'draft', 'label' => 'Draft'], ['value' => 'published', 'label' => 'Dipublikasikan']],
            ]]],
        ]);
    }

    public function create(): Response { return $this->form(); }

    public function store(ArticleRequest $request): RedirectResponse
    {
        Article::create($request->validated());
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article): Response { return $this->form($article); }

    public function update(ArticleRequest $request, Article $article): RedirectResponse
    {
        $article->update($request->validated());
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diarsipkan.');
    }

    private function form(?Article $article = null): Response
    {
        $record = $article?->toArray() ?? [
            'title' => '', 'slug' => '', 'category_id' => '', 'author_id' => auth()->id(), 'excerpt' => '', 'content' => '',
            'cover_image' => '', 'status' => 'draft', 'featured' => false, 'published_at' => '', 'seo_title' => '',
            'meta_description' => '', 'canonical_url' => '', 'og_title' => '', 'og_description' => '', 'og_image' => '',
            'primary_target_keyword' => '', 'secondary_keyword_notes' => '', 'is_indexable' => true,
        ];
        $record['status'] = $article?->status->value ?? 'draft';
        $record['published_at'] = $article?->published_at?->format('Y-m-d\TH:i') ?? '';

        return Inertia::render('Admin/Cms/Form', [
            'formConfig' => [
                'title' => $article ? 'Edit Artikel' : 'Tulis Artikel', 'description' => 'Tulis konten insight yang akurat dan terstruktur.',
                'action' => $article ? route('admin.articles.update', $article) : route('admin.articles.store'),
                'method' => $article ? 'put' : 'post', 'cancelUrl' => route('admin.articles.index'),
                'submitLabel' => $article ? 'Simpan Perubahan' : 'Tambah Artikel', 'slugSource' => 'title',
            ],
            'initial' => $record,
            'sections' => [
                ['title' => 'Informasi artikel', 'fields' => [
                    ['name' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true],
                    ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'required' => true],
                    ['name' => 'category_id', 'label' => 'Kategori artikel', 'type' => 'select', 'options' => $this->categoryOptions()],
                    ['name' => 'author_id', 'label' => 'Penulis', 'type' => 'select', 'options' => $this->authorOptions()],
                    ['name' => 'cover_image', 'label' => 'Referensi cover', 'type' => 'text', 'span' => 2],
                    ['name' => 'excerpt', 'label' => 'Ringkasan', 'type' => 'textarea', 'rows' => 4, 'span' => 2],
                    ['name' => 'content', 'label' => 'Konten', 'type' => 'textarea', 'rows' => 16, 'span' => 2, 'help' => 'Simpan sebagai teks/HTML terkontrol. Sanitasi wajib sebelum kelak dirender ke publik.'],
                ]],
                ['title' => 'Publikasi', 'fields' => [...CmsForm::publicationFields(), ['name' => 'featured', 'label' => 'Tandai sebagai artikel unggulan', 'type' => 'checkbox']]],
                ['title' => 'SEO', 'fields' => CmsForm::seoFields()],
            ],
        ]);
    }

    private function categoryOptions(): array
    {
        return Category::ofType(CategoryType::Article)->ordered()->get(['id', 'name'])->map(fn ($item) => ['value' => $item->id, 'label' => $item->name])->all();
    }

    private function authorOptions(): array
    {
        return User::query()->where('is_admin', true)->orderBy('name')->get(['id', 'name'])->map(fn ($item) => ['value' => $item->id, 'label' => $item->name])->all();
    }
}
