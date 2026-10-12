<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Permintaan project baru</title>
</head>
<body style="margin:0;padding:24px;background:#f7f9fc;color:#091426;font-family:Arial,Helvetica,sans-serif;line-height:1.6">
    <main style="max-width:640px;margin:0 auto;background:#fff;border:1px solid #e2e8f0">
        <div style="background:#091a33;color:#fff;padding:26px 32px">
            <strong style="font-size:20px">SCH Management</strong>
            <p style="margin:8px 0 0;color:#cbd5e1">Permintaan project baru</p>
        </div>
        <div style="padding:28px 32px">
            <p>Permintaan baru telah tersimpan. Detail pengirim:</p>
            <dl>
                <dt><strong>Nama</strong></dt><dd style="margin:0 0 14px">{{ $lead->name }}</dd>
                @if ($lead->company)
                    <dt><strong>Perusahaan</strong></dt><dd style="margin:0 0 14px">{{ $lead->company }}</dd>
                @endif
                <dt><strong>Email</strong></dt><dd style="margin:0 0 14px">{{ $lead->email }}</dd>
                <dt><strong>WhatsApp</strong></dt><dd style="margin:0 0 14px">{{ $lead->whatsapp }}</dd>
                <dt><strong>Jenis Project</strong></dt><dd style="margin:0 0 14px">{{ $lead->project_type }}</dd>
                @if ($lead->budget_range)
                    <dt><strong>Estimasi Budget</strong></dt><dd style="margin:0 0 14px">{{ $lead->budget_range }}</dd>
                @endif
                @if ($lead->target_timeline)
                    <dt><strong>Target Waktu</strong></dt><dd style="margin:0 0 14px">{{ $lead->target_timeline }}</dd>
                @endif
                <dt><strong>Sumber</strong></dt><dd style="margin:0 0 14px">{{ $lead->source === 'request_project' ? 'Konsultasi Project' : 'Kontak' }}</dd>
                <dt><strong>Diterima</strong></dt><dd style="margin:0 0 14px">{{ $lead->created_at->timezone(config('app.timezone'))->format('d M Y H:i') }}</dd>
                <dt><strong>Pesan / Kebutuhan</strong></dt>
                <dd style="margin:6px 0 0;white-space:pre-wrap;overflow-wrap:anywhere">{{ $lead->message }}</dd>
            </dl>
        </div>
    </main>
</body>
</html>
