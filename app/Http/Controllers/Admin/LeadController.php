<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LeadNoteRequest;
use App\Http\Requests\Admin\LeadStatusRequest;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LeadController extends Controller
{
    public function index(Request $request): Response
    {
        $leads = Lead::query()
            ->when($request->string('search')->toString(), function ($query, $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")->orWhere('whatsapp', 'like', "%{$search}%");
                });
            })
            ->when($request->string('status')->toString(), fn ($query, $status) => $query->where('status', $status))
            ->when($request->string('project_type')->toString(), fn ($query, $type) => $query->where('project_type', $type))
            ->latest()->paginate(15)->withQueryString()
            ->through(fn (Lead $lead) => [
                'id' => $lead->id, 'name' => $lead->name, 'company' => $lead->company, 'email' => $lead->email,
                'whatsapp' => $lead->whatsapp, 'project_type' => $lead->project_type, 'budget_range' => $lead->budget_range,
                'status' => $lead->status->value, 'created_at' => $lead->created_at->toISOString(),
                'show_url' => route('admin.leads.show', $lead),
            ]);

        return Inertia::render('Admin/Leads/Index', [
            'leads' => $leads,
            'filters' => $request->only(['search', 'status', 'project_type']),
            'statusOptions' => $this->statusOptions(),
            'projectTypeOptions' => Lead::query()->whereNotNull('project_type')->distinct()->orderBy('project_type')->pluck('project_type'),
        ]);
    }

    public function show(Lead $lead): Response
    {
        $lead->load(['notes.user:id,name']);

        return Inertia::render('Admin/Leads/Show', [
            'lead' => [
                ...$lead->toArray(),
                'status' => $lead->status->value,
                'created_at' => $lead->created_at->toISOString(),
                'updated_at' => $lead->updated_at->toISOString(),
                'email_sent_at' => $lead->email_sent_at?->toISOString(),
                'email_send_failed_at' => $lead->email_send_failed_at?->toISOString(),
                'contacted_at' => $lead->contacted_at?->toISOString(),
                'converted_at' => $lead->converted_at?->toISOString(),
            ],
            'statusOptions' => $this->statusOptions(),
            'statusUrl' => route('admin.leads.status', $lead),
            'noteUrl' => route('admin.leads.notes.store', $lead),
        ]);
    }

    public function updateStatus(LeadStatusRequest $request, Lead $lead): RedirectResponse
    {
        $status = LeadStatus::from($request->validated('status'));
        $updates = ['status' => $status];

        if ($status === LeadStatus::Contacted && $lead->contacted_at === null) {
            $updates['contacted_at'] = now();
        }
        if ($status === LeadStatus::Converted && $lead->converted_at === null) {
            $updates['converted_at'] = now();
        }

        $lead->update($updates);
        return back()->with('success', 'Status lead berhasil diperbarui.');
    }

    public function storeNote(LeadNoteRequest $request, Lead $lead): RedirectResponse
    {
        $lead->notes()->create(['user_id' => $request->user()->id, 'note' => $request->validated('note')]);
        return back()->with('success', 'Catatan internal berhasil ditambahkan.');
    }

    private function statusOptions(): array
    {
        $labels = ['new' => 'Baru', 'contacted' => 'Dihubungi', 'discussion' => 'Diskusi', 'proposal' => 'Proposal', 'negotiation' => 'Negosiasi', 'converted' => 'Terkonversi', 'closed' => 'Ditutup'];
        return collect(LeadStatus::cases())->map(fn ($status) => ['value' => $status->value, 'label' => $labels[$status->value]])->all();
    }
}
