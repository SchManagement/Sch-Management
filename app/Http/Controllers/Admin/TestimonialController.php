<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TestimonialController extends Controller
{
    public function index(Request $request): Response
    {
        $items = Testimonial::query()
            ->with('project:id,title')
            ->when($request->string('search')->toString(), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->when($request->string('status')->toString(), fn ($query, $status) => $query->where('status', $status))
            ->ordered()->paginate(12)->withQueryString()
            ->through(fn (Testimonial $item) => [
                'id' => $item->id, 'name' => $item->name, 'company' => $item->company,
                'project' => $item->project?->title, 'status' => $item->status->value,
                'is_featured' => $item->is_featured, 'sort_order' => $item->sort_order,
                '_links' => ['edit' => route('admin.testimonials.edit', $item), 'delete' => route('admin.testimonials.destroy', $item)],
            ]);

        return Inertia::render('Admin/Cms/Index', [
            'resource' => ['title' => 'Testimoni', 'description' => 'Simpan hanya testimoni asli yang dapat dipertanggungjawabkan.', 'singular' => 'testimoni', 'createLabel' => 'Tambah Testimoni', 'createUrl' => route('admin.testimonials.create')],
            'items' => $items,
            'columns' => [
                ['key' => 'name', 'label' => 'Nama', 'primary' => true], ['key' => 'company', 'label' => 'Perusahaan'],
                ['key' => 'project', 'label' => 'Project'], ['key' => 'status', 'label' => 'Status', 'type' => 'status'],
                ['key' => 'is_featured', 'label' => 'Unggulan', 'type' => 'boolean'], ['key' => 'sort_order', 'label' => 'Urutan', 'type' => 'number'],
            ],
            'filters' => ['search' => $request->string('search')->toString(), 'searchPlaceholder' => 'Cari nama pemberi testimoni...', 'selects' => [[
                'name' => 'status', 'label' => 'Status', 'value' => $request->string('status')->toString(),
                'options' => [['value' => 'draft', 'label' => 'Draft'], ['value' => 'published', 'label' => 'Dipublikasikan']],
            ]]],
        ]);
    }

    public function create(): Response { return $this->form(); }

    public function store(TestimonialRequest $request): RedirectResponse
    {
        Testimonial::create($request->validated());
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function edit(Testimonial $testimonial): Response { return $this->form($testimonial); }

    public function update(TestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($request->validated());
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil dihapus.');
    }

    private function form(?Testimonial $testimonial = null): Response
    {
        $record = $testimonial?->toArray() ?? ['name' => '', 'company' => '', 'position' => '', 'quote' => '', 'photo' => '', 'project_id' => '', 'is_featured' => false, 'status' => 'draft', 'sort_order' => 0];
        $record['status'] = $testimonial?->status->value ?? 'draft';

        return Inertia::render('Admin/Cms/Form', [
            'formConfig' => [
                'title' => $testimonial ? 'Edit Testimoni' : 'Tambah Testimoni', 'description' => 'Catat identitas dan kutipan secara akurat.',
                'action' => $testimonial ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store'),
                'method' => $testimonial ? 'put' : 'post', 'cancelUrl' => route('admin.testimonials.index'),
                'submitLabel' => $testimonial ? 'Simpan Perubahan' : 'Tambah Testimoni',
            ],
            'initial' => $record,
            'sections' => [['title' => 'Detail testimoni', 'fields' => [
                ['name' => 'name', 'label' => 'Nama', 'type' => 'text', 'required' => true],
                ['name' => 'company', 'label' => 'Perusahaan', 'type' => 'text'],
                ['name' => 'position', 'label' => 'Posisi', 'type' => 'text'],
                ['name' => 'project_id', 'label' => 'Project terkait', 'type' => 'select', 'options' => $this->projectOptions()],
                ['name' => 'photo', 'label' => 'Referensi foto', 'type' => 'text', 'span' => 2],
                ['name' => 'quote', 'label' => 'Kutipan', 'type' => 'textarea', 'rows' => 7, 'required' => true, 'span' => 2],
                ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => [['value' => 'draft', 'label' => 'Draft'], ['value' => 'published', 'label' => 'Dipublikasikan']]],
                ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number', 'required' => true, 'min' => 0],
                ['name' => 'is_featured', 'label' => 'Tandai sebagai testimoni unggulan', 'type' => 'checkbox', 'span' => 2],
            ]]],
        ]);
    }

    private function projectOptions(): array
    {
        return Project::ordered()->get(['id', 'title'])->map(fn ($item) => ['value' => $item->id, 'label' => $item->title])->all();
    }
}
