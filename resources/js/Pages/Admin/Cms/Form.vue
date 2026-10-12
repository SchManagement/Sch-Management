<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { nextTick, ref, watch } from 'vue';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import AdminPageHeader from '../../../Components/Admin/AdminPageHeader.vue';
import AdminCard from '../../../Components/Admin/AdminCard.vue';
import AdminButton from '../../../Components/Admin/AdminButton.vue';
import AdminFormField from '../../../Components/Admin/AdminFormField.vue';

const props = defineProps({ formConfig: Object, initial: Object, sections: Array });
const form = useForm({ ...props.initial });
const slugTouched = ref(Boolean(props.initial.slug));
const slugify = (value) => String(value || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
if (props.formConfig.slugSource) watch(() => form[props.formConfig.slugSource], value => { if (!slugTouched.value) form.slug = slugify(value); });
const submit = () => form[props.formConfig.method](props.formConfig.action, { preserveScroll: true, onError: async () => { await nextTick(); document.querySelector('[aria-invalid="true"]')?.focus(); } });
</script>
<template>
    <Head :title="`${formConfig.title} | Admin SCH Management`"><meta name="robots" content="noindex, nofollow"/></Head>
    <AdminLayout>
        <AdminPageHeader :title="formConfig.title" :description="formConfig.description"/>
        <form class="space-y-5" novalidate @submit.prevent="submit">
            <div v-if="Object.keys(form.errors).length" class="rounded-xl border border-red-200 bg-red-50 p-4" role="alert"><p class="font-semibold text-red-800">Periksa kembali isian yang ditandai.</p><p class="mt-1 text-sm text-red-700">Terdapat {{ Object.keys(form.errors).length }} bagian yang perlu diperbaiki.</p></div>
            <AdminCard v-for="section in sections" :key="section.title" class="p-5 sm:p-6"><header class="mb-5 border-b border-sch-border pb-4"><h2 class="text-base font-bold text-sch-navy">{{ section.title }}</h2><p v-if="section.description" class="mt-1 text-sm leading-6 text-sch-muted">{{ section.description }}</p></header><div class="grid gap-5 md:grid-cols-2"><AdminFormField v-for="field in section.fields" :key="field.name" v-model="form[field.name]" :field="field" :error="form.errors[field.name]" @update:model-value="field.name === 'slug' ? slugTouched = true : null"/></div></AdminCard>
            <div class="sticky bottom-3 z-20 flex flex-col-reverse gap-2 rounded-2xl border border-sch-border bg-white/95 p-3 shadow-xl backdrop-blur sm:flex-row sm:justify-end"><AdminButton :href="formConfig.cancelUrl" variant="secondary">Batal</AdminButton><AdminButton type="submit" :disabled="form.processing">{{ form.processing ? 'Menyimpan...' : formConfig.submitLabel }}</AdminButton></div>
        </form>
    </AdminLayout>
</template>
