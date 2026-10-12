SCH Management — Permintaan Project Baru

Nama: {{ $lead->name }}
@if ($lead->company)
Perusahaan: {{ $lead->company }}
@endif
Email: {{ $lead->email }}
WhatsApp: {{ $lead->whatsapp }}
Jenis Project: {{ $lead->project_type }}
@if ($lead->budget_range)
Estimasi Budget: {{ $lead->budget_range }}
@endif
@if ($lead->target_timeline)
Target Waktu: {{ $lead->target_timeline }}
@endif
Sumber: {{ $lead->source === 'request_project' ? 'Konsultasi Project' : 'Kontak' }}
Diterima: {{ $lead->created_at->timezone(config('app.timezone'))->format('d M Y H:i') }}

Pesan / Kebutuhan:
{{ $lead->message }}
