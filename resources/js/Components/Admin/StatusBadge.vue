<script setup>
const props = defineProps({ value: { type: [String, Boolean], required: true } });
const labels = { draft: 'Draft', published: 'Dipublikasikan', new: 'Baru', contacted: 'Dihubungi', discussion: 'Diskusi', proposal: 'Proposal', negotiation: 'Negosiasi', converted: 'Terkonversi', closed: 'Ditutup', true: 'Ya', false: 'Tidak' };
const tone = { published: 'bg-emerald-50 text-emerald-700 ring-emerald-200', converted: 'bg-emerald-50 text-emerald-700 ring-emerald-200', new: 'bg-blue-50 text-blue-700 ring-blue-200', contacted: 'bg-cyan-50 text-cyan-700 ring-cyan-200', discussion: 'bg-violet-50 text-violet-700 ring-violet-200', proposal: 'bg-amber-50 text-amber-800 ring-amber-200', negotiation: 'bg-orange-50 text-orange-800 ring-orange-200', closed: 'bg-slate-100 text-slate-700 ring-slate-200', draft: 'bg-slate-100 text-slate-700 ring-slate-200', true: 'bg-emerald-50 text-emerald-700 ring-emerald-200', false: 'bg-slate-100 text-slate-700 ring-slate-200' };
const key = String(props.value);
</script>
<template><span class="inline-flex max-w-full shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset" :class="tone[key] || tone.draft"><span class="size-1.5 rounded-full bg-current" aria-hidden="true"/>{{ labels[key] || value }}</span></template>
