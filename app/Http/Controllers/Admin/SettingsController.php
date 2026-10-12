<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanySettingsRequest;
use App\Http\Requests\Admin\GeneralSettingsRequest;
use App\Http\Requests\Admin\SeoSettingsRequest;
use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    private const COMPANY_MAP = [
        'company_name' => ['company', 'name', 'string'], 'tagline' => ['company', 'tagline', 'string'],
        'company_email' => ['company', 'email', 'string'], 'whatsapp' => ['company', 'whatsapp', 'string'],
        'contact_recipient_email' => ['contact', 'recipient_email', 'string'],
        'website' => ['company', 'website', 'string'], 'founder_name' => ['company', 'founder_name', 'string'],
        'founder_position' => ['company', 'founder_position', 'string'], 'founder_biography' => ['company', 'founder_biography', 'text'],
        'linkedin' => ['social', 'linkedin', 'string'], 'instagram' => ['social', 'instagram', 'string'],
    ];

    private const SEO_MAP = [
        'default_title' => ['seo', 'default_title', 'string'], 'title_suffix' => ['seo', 'title_suffix', 'string'],
        'default_description' => ['seo', 'default_description', 'text'], 'default_og_image' => ['seo', 'default_og_image', 'string'],
        'canonical_base_url' => ['seo', 'canonical_base_url', 'string'], 'google_site_verification' => ['seo', 'google_site_verification', 'string'],
        'indexing_enabled' => ['seo', 'indexing_enabled', 'boolean'], 'ga_measurement_id' => ['analytics', 'ga_measurement_id', 'string'],
    ];

    private const GENERAL_MAP = [
        'site_name' => ['site', 'name', 'string'], 'site_description' => ['site', 'description', 'text'],
        'default_contact_url' => ['site', 'default_contact_url', 'string'],
    ];

    public function company(): Response
    {
        return $this->renderSettings(
            'Informasi Perusahaan',
            'Kelola informasi SCH Management yang benar-benar dapat dipublikasikan.',
            route('admin.company.update'),
            self::COMPANY_MAP,
            [[
                'title' => 'Identitas dan kontak',
                'fields' => [
                    ['name' => 'company_name', 'label' => 'Nama perusahaan', 'type' => 'text'],
                    ['name' => 'tagline', 'label' => 'Tagline', 'type' => 'text'],
                    ['name' => 'company_email', 'label' => 'Email perusahaan', 'type' => 'email'],
                    ['name' => 'contact_recipient_email', 'label' => 'Email penerima permintaan project', 'type' => 'email', 'help' => 'Notifikasi lead baru dikirim ke alamat ini. Jika kosong, sistem menggunakan penerima fallback.'],
                    ['name' => 'whatsapp', 'label' => 'Nomor WhatsApp', 'type' => 'tel', 'maxlength' => 64, 'help' => 'Masukkan nomor saja. Format seperti +62 812-3456-7890 akan otomatis diarahkan ke wa.me.'],
                    ['name' => 'website', 'label' => 'Website', 'type' => 'url'],
                    ['name' => 'linkedin', 'label' => 'URL LinkedIn', 'type' => 'url', 'maxlength' => 2048, 'help' => 'Contoh: https://www.linkedin.com/company/schmanagement'],
                    ['name' => 'instagram', 'label' => 'URL Instagram', 'type' => 'url', 'maxlength' => 2048, 'help' => 'Contoh: https://www.instagram.com/schmanagement'],
                ],
            ], [
                'title' => 'Founder',
                'description' => 'Isi hanya dengan informasi pendiri yang sah.',
                'fields' => [
                    ['name' => 'founder_name', 'label' => 'Nama founder', 'type' => 'text'],
                    ['name' => 'founder_position', 'label' => 'Posisi', 'type' => 'text'],
                    ['name' => 'founder_biography', 'label' => 'Biografi', 'type' => 'textarea', 'rows' => 8, 'span' => 2],
                ],
            ]],
        );
    }

    public function updateCompany(CompanySettingsRequest $request): RedirectResponse
    {
        $this->persist($request, self::COMPANY_MAP);
        return back()->with('success', 'Informasi perusahaan berhasil disimpan.');
    }

    public function seo(): Response
    {
        return $this->renderSettings(
            'Pengaturan SEO',
            'Kelola default SEO global. Metadata per halaman tetap dapat mengambil alih nilai ini.',
            route('admin.seo.update'),
            self::SEO_MAP,
            [[
                'title' => 'Metadata default',
                'fields' => [
                    ['name' => 'default_title', 'label' => 'Judul SEO default', 'type' => 'text'],
                    ['name' => 'title_suffix', 'label' => 'Akhiran judul', 'type' => 'text'],
                    ['name' => 'default_description', 'label' => 'Meta description default', 'type' => 'textarea', 'rows' => 4, 'span' => 2],
                    ['name' => 'default_og_image', 'label' => 'OG image default', 'type' => 'text'],
                    ['name' => 'canonical_base_url', 'label' => 'Canonical base URL', 'type' => 'url'],
                    ['name' => 'indexing_enabled', 'label' => 'Izinkan indexing pada lingkungan ini', 'type' => 'checkbox', 'span' => 2],
                ],
            ], [
                'title' => 'Integrasi Google',
                'description' => 'Biarkan kosong sampai token/ID yang sah tersedia.',
                'fields' => [
                    ['name' => 'google_site_verification', 'label' => 'Google Site Verification', 'type' => 'text'],
                    ['name' => 'ga_measurement_id', 'label' => 'Google Analytics ID', 'type' => 'text', 'help' => 'Format: G-XXXXXXXXXX'],
                ],
            ]],
        );
    }

    public function updateSeo(SeoSettingsRequest $request): RedirectResponse
    {
        $this->persist($request, self::SEO_MAP);
        return back()->with('success', 'Pengaturan SEO berhasil disimpan.');
    }

    public function general(): Response
    {
        return $this->renderSettings(
            'Pengaturan Umum',
            'Pengaturan publik yang aman. Kredensial dan secret tidak pernah ditampilkan di sini.',
            route('admin.settings.update'),
            self::GENERAL_MAP,
            [['title' => 'Situs', 'fields' => [
                ['name' => 'site_name', 'label' => 'Nama situs', 'type' => 'text'],
                ['name' => 'default_contact_url', 'label' => 'URL kontak default', 'type' => 'text'],
                ['name' => 'site_description', 'label' => 'Deskripsi situs', 'type' => 'textarea', 'rows' => 5, 'span' => 2],
            ]]],
        );
    }

    public function updateGeneral(GeneralSettingsRequest $request): RedirectResponse
    {
        $this->persist($request, self::GENERAL_MAP);
        return back()->with('success', 'Pengaturan umum berhasil disimpan.');
    }

    private function renderSettings(string $title, string $description, string $action, array $map, array $sections): Response
    {
        return Inertia::render('Admin/Settings/Edit', [
            'formConfig' => ['title' => $title, 'description' => $description, 'action' => $action, 'submitLabel' => 'Simpan Pengaturan'],
            'initial' => $this->values($map),
            'sections' => $sections,
        ]);
    }

    private function values(array $map): array
    {
        $settings = Setting::query()->get()->keyBy(fn (Setting $setting) => "{$setting->group}.{$setting->key}");
        $values = [];
        foreach ($map as $field => [$group, $key, $type]) {
            $value = $settings->get("{$group}.{$key}")?->value;
            $values[$field] = $type === 'boolean'
                ? ($value === null && $field === 'indexing_enabled' ? true : filter_var($value, FILTER_VALIDATE_BOOLEAN))
                : ($value ?? '');
        }
        return $values;
    }

    private function persist(FormRequest $request, array $map): void
    {
        $validated = $request->validated();
        DB::transaction(function () use ($validated, $map): void {
            foreach ($map as $field => [$group, $key, $type]) {
                $value = $validated[$field] ?? null;
                if ($type === 'boolean') {
                    $value = $value ? '1' : '0';
                }
                Setting::updateOrCreate(
                    ['group' => $group, 'key' => $key],
                    ['value' => $value === '' ? null : $value, 'type' => $type, 'is_public' => $key !== 'recipient_email'],
                );
            }
        });
    }
}
