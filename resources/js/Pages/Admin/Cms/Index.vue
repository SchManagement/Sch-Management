<script setup>
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import AdminPageHeader from '../../../Components/Admin/AdminPageHeader.vue';
import AdminCard from '../../../Components/Admin/AdminCard.vue';
import AdminButton from '../../../Components/Admin/AdminButton.vue';
import AdminIcon from '../../../Components/Admin/AdminIcon.vue';
import AdminPagination from '../../../Components/Admin/AdminPagination.vue';
import StatusBadge from '../../../Components/Admin/StatusBadge.vue';
import EmptyState from '../../../Components/Admin/EmptyState.vue';
import ConfirmDialog from '../../../Components/Admin/ConfirmDialog.vue';

const props = defineProps({ resource: Object, items: Object, columns: Array, filters: Object });
const page = usePage();
const form = useForm({ search: props.filters.search || '', ...Object.fromEntries((props.filters.selects || []).map(item => [item.name, item.value || ''])) });
const target = ref(null);
const deleting = ref(false);
const applyFilters = () => form.get(page.url.split('?')[0], { preserveState: true, preserveScroll: true, replace: true });
const reset = () => { form.search = ''; (props.filters.selects || []).forEach(item => form[item.name] = ''); applyFilters(); };
const remove = () => { deleting.value = true; router.delete(target.value._links.delete, { preserveScroll: true, onFinish: () => { deleting.value = false; target.value = null; } }); };
const formatDate = (value) => value ? new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium' }).format(new Date(value)) : '—';
const display = (row, column) => column.type === 'date' ? formatDate(row[column.key]) : (row[column.key] || row[column.key] === 0 ? row[column.key] : '—');
</script>
<template>
    <Head :title="`${resource.title} | Admin SCH Management`"><meta name="robots" content="noindex, nofollow"/></Head>
    <AdminLayout>
        <AdminPageHeader :title="resource.title" :description="resource.description" :action-label="resource.createLabel" :action-url="resource.createUrl"/>
        <AdminCard>
            <form v-if="filters.searchPlaceholder || filters.selects?.length" class="flex flex-col gap-3 border-b border-sch-border p-4 sm:flex-row sm:items-end" role="search" @submit.prevent="applyFilters">
                <div v-if="filters.searchPlaceholder" class="min-w-0 flex-1"><label for="cms-search" class="mb-1.5 block text-xs font-semibold text-sch-muted">Pencarian</label><div class="relative"><AdminIcon name="search" class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-sch-muted"/><input id="cms-search" v-model="form.search" type="search" :placeholder="filters.searchPlaceholder" class="min-h-11 w-full rounded-xl border border-sch-border bg-white pl-10 pr-4 text-sm focus:border-sch-blue focus:ring-3 focus:ring-blue-100"/></div></div>
                <div v-for="select in filters.selects || []" :key="select.name" class="sm:w-48"><label :for="`filter-${select.name}`" class="mb-1.5 block text-xs font-semibold text-sch-muted">{{ select.label }}</label><select :id="`filter-${select.name}`" v-model="form[select.name]" class="min-h-11 w-full rounded-xl border border-sch-border bg-white px-3 text-sm"><option value="">Semua</option><option v-for="option in select.options" :key="option.value" :value="option.value">{{ option.label }}</option></select></div>
                <div class="flex gap-2"><AdminButton type="submit" :disabled="form.processing">Terapkan</AdminButton><AdminButton type="button" variant="secondary" @click="reset">Reset</AdminButton></div>
            </form>
            <EmptyState v-if="!items.data.length" :title="`Belum ada ${resource.singular}.`" description="Data asli yang ditambahkan administrator akan muncul di sini." :action-label="resource.createLabel" :action-url="resource.createUrl"/>
            <template v-else>
                <div class="hidden overflow-x-auto md:block"><table class="w-full min-w-[760px] text-left text-sm"><thead class="border-b border-sch-border bg-slate-50/80 text-xs uppercase tracking-wide text-sch-muted"><tr><th v-for="column in columns" :key="column.key" scope="col" class="px-5 py-3.5 font-semibold">{{ column.label }}</th><th scope="col" class="px-5 py-3.5 text-right font-semibold">Aksi</th></tr></thead><tbody class="divide-y divide-sch-border"><tr v-for="row in items.data" :key="row.id" class="hover:bg-slate-50/70"><td v-for="column in columns" :key="column.key" class="max-w-xs px-5 py-4" :class="column.primary ? 'font-semibold text-sch-navy' : 'text-sch-muted'"><StatusBadge v-if="column.type === 'status' || column.type === 'boolean'" :value="row[column.key]"/><span v-else class="line-clamp-2">{{ display(row, column) }}</span></td><td class="px-5 py-4"><div class="flex justify-end gap-1"><AdminButton :href="row._links.edit" variant="ghost" icon="edit">Edit</AdminButton><AdminButton variant="ghost" icon="trash" class="text-red-700" @click="target = row">Hapus</AdminButton></div></td></tr></tbody></table></div>
                <div class="divide-y divide-sch-border md:hidden"><article v-for="row in items.data" :key="row.id" class="p-5"><div class="space-y-3"><div v-for="column in columns" :key="column.key" class="flex items-start justify-between gap-5"><span class="text-xs font-semibold text-sch-muted">{{ column.label }}</span><StatusBadge v-if="column.type === 'status' || column.type === 'boolean'" :value="row[column.key]"/><span v-else class="break-words text-right text-sm" :class="column.primary ? 'font-semibold text-sch-navy' : 'text-sch-muted'">{{ display(row, column) }}</span></div></div><div class="mt-4 flex gap-2 border-t border-sch-border pt-4"><AdminButton :href="row._links.edit" variant="secondary" icon="edit" class="flex-1">Edit</AdminButton><AdminButton variant="danger" icon="trash" class="flex-1" @click="target = row">Hapus</AdminButton></div></article></div>
                <AdminPagination :links="items.links" :from="items.from" :to="items.to" :total="items.total"/>
            </template>
        </AdminCard>
        <ConfirmDialog :open="Boolean(target)" title="Konfirmasi penghapusan" :message="`Tindakan ini akan menghapus atau mengarsipkan ${resource.singular}. Lanjutkan?`" :busy="deleting" @close="target = null" @confirm="remove"/>
    </AdminLayout>
</template>
