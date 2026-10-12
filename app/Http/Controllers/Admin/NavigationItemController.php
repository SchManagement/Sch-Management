<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NavigationItemRequest;
use App\Models\NavigationItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NavigationItemController extends Controller
{
    public function index(Request $request): Response
    {
        $location = $request->string('location')->toString();
        $items = NavigationItem::query()
            ->with('parent:id,label')
            ->when($location, fn ($query, $value) => $query->forLocation($value))
            ->ordered()->paginate(20)->withQueryString()
            ->through(fn (NavigationItem $item) => [
                'id' => $item->id, 'label' => $item->label, 'location' => $item->location,
                'destination' => $item->route_name ?: $item->url, 'parent' => $item->parent?->label,
                'sort_order' => $item->sort_order, 'is_active' => $item->is_active, 'target' => $item->target,
                '_links' => ['edit' => route('admin.navigation.edit', $item), 'delete' => route('admin.navigation.destroy', $item)],
            ]);

        return Inertia::render('Admin/Cms/Index', [
            'resource' => ['title' => $location === 'footer' ? 'Navigasi Footer' : 'Navigasi', 'description' => 'Atur tautan header dan footer dengan urutan numerik yang stabil.', 'singular' => 'item navigasi', 'createLabel' => 'Tambah Item', 'createUrl' => route('admin.navigation.create')],
            'items' => $items,
            'columns' => [
                ['key' => 'label', 'label' => 'Label', 'primary' => true], ['key' => 'location', 'label' => 'Lokasi'],
                ['key' => 'destination', 'label' => 'Tujuan'], ['key' => 'parent', 'label' => 'Induk'],
                ['key' => 'sort_order', 'label' => 'Urutan', 'type' => 'number'], ['key' => 'is_active', 'label' => 'Aktif', 'type' => 'boolean'],
            ],
            'filters' => ['search' => '', 'searchPlaceholder' => '', 'selects' => [[
                'name' => 'location', 'label' => 'Lokasi', 'value' => $location,
                'options' => [['value' => 'header', 'label' => 'Header'], ['value' => 'footer', 'label' => 'Footer']],
            ]]],
        ]);
    }

    public function create(): Response { return $this->form(); }

    public function store(NavigationItemRequest $request): RedirectResponse
    {
        NavigationItem::create($request->validated());
        return redirect()->route('admin.navigation.index')->with('success', 'Item navigasi berhasil ditambahkan.');
    }

    public function edit(NavigationItem $navigation): Response { return $this->form($navigation); }

    public function update(NavigationItemRequest $request, NavigationItem $navigation): RedirectResponse
    {
        $navigation->update($request->validated());
        return redirect()->route('admin.navigation.index')->with('success', 'Item navigasi berhasil diperbarui.');
    }

    public function destroy(NavigationItem $navigation): RedirectResponse
    {
        $navigation->delete();
        return redirect()->route('admin.navigation.index')->with('success', 'Item navigasi berhasil dihapus.');
    }

    private function form(?NavigationItem $navigation = null): Response
    {
        $record = $navigation?->toArray() ?? ['location' => 'header', 'label' => '', 'url' => '', 'route_name' => '', 'parent_id' => '', 'sort_order' => 0, 'is_active' => true, 'target' => '_self'];

        return Inertia::render('Admin/Cms/Form', [
            'formConfig' => [
                'title' => $navigation ? 'Edit Item Navigasi' : 'Tambah Item Navigasi', 'description' => 'Isi URL atau nama route. Sistem mencegah induk diri sendiri dan hierarki berputar.',
                'action' => $navigation ? route('admin.navigation.update', $navigation) : route('admin.navigation.store'),
                'method' => $navigation ? 'put' : 'post', 'cancelUrl' => route('admin.navigation.index'),
                'submitLabel' => $navigation ? 'Simpan Perubahan' : 'Tambah Item',
            ],
            'initial' => $record,
            'sections' => [['title' => 'Detail navigasi', 'fields' => [
                ['name' => 'location', 'label' => 'Lokasi', 'type' => 'select', 'required' => true, 'options' => [['value' => 'header', 'label' => 'Header'], ['value' => 'footer', 'label' => 'Footer']]],
                ['name' => 'label', 'label' => 'Label', 'type' => 'text', 'required' => true],
                ['name' => 'url', 'label' => 'URL', 'type' => 'text', 'help' => 'Contoh: /about atau https://domain.tld.'],
                ['name' => 'route_name', 'label' => 'Nama route Laravel', 'type' => 'text'],
                ['name' => 'parent_id', 'label' => 'Item induk', 'type' => 'select', 'options' => $this->parentOptions($navigation)],
                ['name' => 'target', 'label' => 'Target', 'type' => 'select', 'required' => true, 'options' => [['value' => '_self', 'label' => 'Tab yang sama'], ['value' => '_blank', 'label' => 'Tab baru']]],
                ['name' => 'sort_order', 'label' => 'Urutan', 'type' => 'number', 'required' => true, 'min' => 0],
                ['name' => 'is_active', 'label' => 'Aktifkan item navigasi', 'type' => 'checkbox'],
            ]]],
        ]);
    }

    private function parentOptions(?NavigationItem $current): array
    {
        return NavigationItem::query()->when($current, fn ($query) => $query->whereKeyNot($current->getKey()))
            ->ordered()->get(['id', 'label', 'location'])
            ->map(fn ($item) => ['value' => $item->id, 'label' => "{$item->label} ({$item->location})"])->all();
    }
}
