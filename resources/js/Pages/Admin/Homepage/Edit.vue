<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import AdminPageHeader from '../../../Components/Admin/AdminPageHeader.vue';
import AdminCard from '../../../Components/Admin/AdminCard.vue';
import AdminButton from '../../../Components/Admin/AdminButton.vue';

const props = defineProps({ sections: Array, referenceOptions: Object });
const form = useForm({ sections: props.sections.map(({ section_key, heading, content_json, is_enabled, sort_order }) => ({ section_key, heading, content_json, is_enabled, sort_order })) });
const submit = () => form.put('/admin/homepage', { preserveScroll: true });
const selectedIds = index => {
    try { return JSON.parse(form.sections[index].content_json || '{}').selected_ids || []; } catch { return []; }
};
const toggleReference = (index, id, checked) => {
    let content;
    try { content = JSON.parse(form.sections[index].content_json || '{}'); } catch { content = {}; }
    const ids = new Set(content.selected_ids || []);
    checked ? ids.add(id) : ids.delete(id);
    content.selected_ids = [...ids];
    form.sections[index].content_json = JSON.stringify(content, null, 2);
};
</script>

<template>
    <Head title="Homepage CMS | Admin SCH Management"><meta name="robots" content="noindex, nofollow"/></Head>
    <AdminLayout>
        <AdminPageHeader title="Homepage" description="Kelola sembilan bagian homepage secara terstruktur. JSON hanya untuk konfigurasi section, bukan salinan record domain."/>
        <form class="min-w-0 w-full max-w-full space-y-4" @submit.prevent="submit">
            <div v-if="Object.keys(form.errors).length" role="alert" class="min-w-0 w-full max-w-full rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800">Periksa JSON atau nilai section yang ditandai.</div>
            <AdminCard v-for="(section, index) in sections" :key="section.section_key" class="min-w-0 max-w-full p-4 last-of-type:mb-20 sm:p-6 sm:last-of-type:mb-0">
                <div class="flex flex-col gap-4 border-b border-sch-border pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0"><p class="break-words text-xs font-bold uppercase tracking-[0.12em] text-sch-blue">{{ section.section_key }}</p><h2 class="mt-1 break-words text-lg font-bold text-sch-navy">{{ section.label }}</h2></div>
                    <label class="flex min-h-11 shrink-0 items-center gap-3 rounded-xl border border-sch-border px-4 text-sm font-semibold text-sch-navy"><input v-model="form.sections[index].is_enabled" type="checkbox" class="size-5 shrink-0 rounded border-slate-300 text-sch-blue focus:ring-sch-blue"/>Aktif</label>
                </div>
                <div class="mt-5 grid min-w-0 gap-5 lg:grid-cols-[minmax(0,1fr)_8rem]">
                    <div class="min-w-0 max-w-full"><label :for="`heading-${index}`" class="mb-2 block text-sm font-semibold text-sch-navy">Heading</label><input :id="`heading-${index}`" v-model="form.sections[index].heading" type="text" class="min-h-11 min-w-0 w-full max-w-full rounded-xl border border-sch-border px-3.5 text-base focus:border-sch-blue focus:ring-3 focus:ring-blue-100 sm:text-sm"/><p v-if="form.errors[`sections.${index}.heading`]" class="mt-1.5 break-words text-sm text-red-700">{{ form.errors[`sections.${index}.heading`] }}</p></div>
                    <div class="min-w-0 max-w-full"><label :for="`order-${index}`" class="mb-2 block text-sm font-semibold text-sch-navy">Urutan</label><input :id="`order-${index}`" v-model="form.sections[index].sort_order" type="number" min="0" class="min-h-11 min-w-0 w-full max-w-full rounded-xl border border-sch-border px-3.5 text-base sm:text-sm"/></div>
                    <fieldset v-if="referenceOptions[section.section_key]?.length" class="min-w-0 max-w-full lg:col-span-2">
                        <legend class="text-sm font-semibold text-sch-navy">Konten terpilih</legend><p class="mt-1 text-xs text-sch-muted">Pilihan disimpan sebagai ID referensi, bukan salinan record.</p>
                        <div class="mt-3 grid min-w-0 gap-2 sm:grid-cols-2 xl:grid-cols-3"><label v-for="item in referenceOptions[section.section_key]" :key="item.id" class="flex min-h-11 min-w-0 w-full max-w-full items-start gap-3 rounded-xl border border-sch-border px-3 py-2.5 text-sm hover:bg-slate-50"><input type="checkbox" class="mt-0.5 size-5 shrink-0 rounded border-slate-300 text-sch-blue" :checked="selectedIds(index).map(String).includes(String(item.id))" @change="toggleReference(index, item.id, $event.target.checked)"/><span class="min-w-0 flex-1 break-words font-medium leading-5 text-sch-navy">{{ item.label }}</span><span class="shrink-0 text-xs leading-5 text-sch-muted">{{ item.status }}</span></label></div>
                    </fieldset>
                    <div class="min-w-0 max-w-full lg:col-span-2"><label :for="`content-${index}`" class="mb-2 block text-sm font-semibold text-sch-navy">Konfigurasi JSON</label><textarea :id="`content-${index}`" v-model="form.sections[index].content_json" rows="7" spellcheck="false" class="min-w-0 w-full max-w-full scroll-mb-28 resize-y rounded-xl border border-sch-border bg-slate-950 px-3 py-3 font-mono text-base leading-6 text-slate-100 focus:border-sch-blue focus:ring-3 focus:ring-blue-100 sm:px-4 sm:text-sm" placeholder='{"description": "..."}'/><p class="mt-1.5 break-words text-xs leading-5 text-sch-muted">Gunakan JSON hanya untuk konfigurasi section; jangan duplikasi record domain lengkap.</p><p v-if="form.errors[`sections.${index}.content_json`]" class="mt-1.5 break-words text-sm font-medium text-red-700">{{ form.errors[`sections.${index}.content_json`] }}</p></div>
                </div>
            </AdminCard>
            <div class="sticky bottom-0 z-20 flex min-w-0 w-full max-w-full justify-end rounded-2xl border border-sch-border bg-white/95 p-3 [padding-bottom:calc(0.75rem+env(safe-area-inset-bottom))] shadow-xl backdrop-blur sm:bottom-3 sm:[padding-bottom:0.75rem]"><AdminButton type="submit" class="w-full sm:w-auto" :disabled="form.processing">{{ form.processing ? 'Menyimpan...' : 'Simpan Homepage' }}</AdminButton></div>
        </form>
    </AdminLayout>
</template>
