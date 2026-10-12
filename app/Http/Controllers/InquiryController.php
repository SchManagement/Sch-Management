<?php

namespace App\Http\Controllers;

use App\Http\Requests\Public\ContactRequest;
use App\Http\Requests\Public\RequestProjectRequest;
use App\Support\LeadSubmissionService;
use App\Support\RecaptchaVerifier;
use App\Support\PublicSeo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class InquiryController extends Controller
{
    public function contact(PublicSeo $seo): Response
    {
        return Inertia::render('Inquiry/Contact', [...$this->formData(), 'seo' => $seo->page(
            '/contact', 'Konsultasi Pembuatan Website | SCH Management',
            'Hubungi SCH Management untuk mendiskusikan kebutuhan website, aplikasi web, dan solusi digital bisnis Anda.',
            ['breadcrumbs' => [['label' => 'Beranda', 'path' => '/'], ['label' => 'Kontak', 'path' => '/contact']]]),
        ]);
    }

    public function requestProject(PublicSeo $seo): Response
    {
        return Inertia::render('Inquiry/RequestProject', [...$this->formData(), 'seo' => $seo->page(
            '/request-project', 'Diskusikan Project | SCH Management',
            'Diskusikan kebutuhan website, aplikasi web, atau solusi IT bisnis Anda melalui konsultasi project SCH Management.',
            ['noindex' => true, 'breadcrumbs' => [['label' => 'Beranda', 'path' => '/'], ['label' => 'Konsultasi Project', 'path' => '/request-project']]]),
        ]);
    }

    public function storeContact(
        ContactRequest $request,
        RecaptchaVerifier $recaptcha,
        LeadSubmissionService $submissions,
    ): RedirectResponse {
        return $this->store($request, 'contact', $recaptcha, $submissions);
    }

    public function storeRequestProject(
        RequestProjectRequest $request,
        RecaptchaVerifier $recaptcha,
        LeadSubmissionService $submissions,
    ): RedirectResponse {
        return $this->store($request, 'request_project', $recaptcha, $submissions);
    }

    /** @return array<string, mixed> */
    private function formData(): array
    {
        $siteKey = config('services.recaptcha.site_key');
        $secret = config('services.recaptcha.secret_key');

        return [
            'projectTypes' => collect(config('inquiry.project_types'))
                ->map(fn (string $value): array => ['value' => $value, 'label' => $value])->all(),
            'budgetRanges' => collect(config('inquiry.budget_ranges'))
                ->map(fn (string $value): array => ['value' => $value, 'label' => $value])->all(),
            'recaptchaSiteKey' => is_string($siteKey) && trim($siteKey) !== ''
                && is_string($secret) && trim($secret) !== '' ? $siteKey : null,
        ];
    }

    private function store(
        ContactRequest|RequestProjectRequest $request,
        string $source,
        RecaptchaVerifier $recaptcha,
        LeadSubmissionService $submissions,
    ): RedirectResponse {
        $validated = $request->validated();

        if (! $recaptcha->verify($validated['g-recaptcha-response'], $request->ip())) {
            throw ValidationException::withMessages([
                'g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal. Silakan coba kembali.',
            ]);
        }

        $submissions->submit($validated, $source);

        return back()->with('success', 'Permintaan Anda telah berhasil dikirim.');
    }
}
