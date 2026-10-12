<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminLayout from '../../Layouts/AdminLayout.vue';
import AdminPageHeader from '../../Components/Admin/AdminPageHeader.vue';
import AdminCard from '../../Components/Admin/AdminCard.vue';
import AdminButton from '../../Components/Admin/AdminButton.vue';
import AdminIcon from '../../Components/Admin/AdminIcon.vue';
import StatusBadge from '../../Components/Admin/StatusBadge.vue';

const props = defineProps({ stats: Object, recentLeads: Array, latestProjects: Array, latestArticles: Array });
const administrator = computed(() => usePage().props.auth?.user);
const cards = computed(() => [
    { label: 'Total Projects', value: props.stats.total_projects, icon: 'projects', href: '/admin/projects' },
    { label: 'Published Projects', value: props.stats.published_projects, icon: 'check', href: '/admin/projects?status=published' },
    { label: 'New Leads', value: props.stats.new_leads, icon: 'leads', href: '/admin/leads?status=new' },
    { label: 'Published Articles', value: props.stats.published_articles, icon: 'articles', href: '/admin/articles?status=published' },
]);
const date = value => new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium' }).format(new Date(value));
</script>
<template>
    <Head title="Dashboard Admin | SCH Management"><meta name="robots" content="noindex, nofollow"/></Head>
    <AdminLayout>
        <AdminPageHeader title="Dashboard" :description="`Selamat datang, ${administrator?.name}. Berikut ringkasan data aktual SCH Management.`"/>
        <section aria-label="Ringkasan data" class="grid min-w-0 gap-4 sm:grid-cols-2 xl:grid-cols-4"><Link v-for="card in cards" :key="card.label" :href="card.href" class="group min-w-0 w-full max-w-full rounded-2xl border border-sch-border bg-white p-5 shadow-[0_8px_24px_rgba(9,26,51,0.04)] transition-colors hover:border-blue-200"><div class="flex min-w-0 items-start justify-between gap-4"><div class="min-w-0"><p class="text-sm font-medium text-sch-muted">{{ card.label }}</p><p class="mt-2 text-3xl font-bold tracking-tight text-sch-navy">{{ card.value }}</p></div><span class="grid size-11 shrink-0 place-items-center rounded-xl bg-sch-soft-blue text-sch-blue group-hover:bg-blue-100"><AdminIcon :name="card.icon" class="size-5"/></span></div></Link></section>
        <div class="mt-6 grid min-w-0 gap-5 xl:grid-cols-[minmax(0,1.25fr)_minmax(20rem,.75fr)]">
            <div class="min-w-0 space-y-5"><AdminCard><header class="flex min-w-0 items-center justify-between gap-3 border-b border-sch-border px-4 py-4 sm:px-5"><h2 class="min-w-0 font-bold text-sch-navy">Recent Leads</h2><Link href="/admin/leads" class="shrink-0 text-sm font-semibold text-sch-blue hover:underline">Lihat semua</Link></header><div v-if="recentLeads.length" class="divide-y divide-sch-border"><Link v-for="lead in recentLeads" :key="lead.id" :href="`/admin/leads/${lead.id}`" class="flex min-w-0 flex-col items-start gap-2 px-4 py-4 hover:bg-slate-50 sm:flex-row sm:items-center sm:justify-between sm:gap-4 sm:px-5"><div class="min-w-0 w-full"><p class="break-words text-sm font-semibold text-sch-navy sm:truncate">{{ lead.name }}</p><p class="mt-1 break-words text-xs text-sch-muted sm:truncate">{{ lead.company || lead.project_type }}</p></div><StatusBadge :value="lead.status"/></Link></div><p v-else class="px-4 py-10 text-center text-sm text-sch-muted sm:px-5">Belum ada lead.</p></AdminCard>
                <AdminCard><header class="flex min-w-0 items-center justify-between gap-3 border-b border-sch-border px-4 py-4 sm:px-5"><h2 class="min-w-0 font-bold text-sch-navy">Latest Projects</h2><Link href="/admin/projects" class="shrink-0 text-sm font-semibold text-sch-blue hover:underline">Lihat semua</Link></header><div v-if="latestProjects.length" class="divide-y divide-sch-border"><Link v-for="project in latestProjects" :key="project.id" :href="`/admin/projects/${project.slug}/edit`" class="flex min-w-0 flex-col items-start gap-2 px-4 py-4 hover:bg-slate-50 sm:flex-row sm:items-center sm:justify-between sm:gap-4 sm:px-5"><div class="min-w-0 w-full"><p class="break-words text-sm font-semibold text-sch-navy sm:truncate">{{ project.title }}</p><p class="mt-1 break-words text-xs leading-5 text-sch-muted">{{ project.category?.name || 'Tanpa kategori' }} · {{ date(project.updated_at) }}</p></div><StatusBadge :value="project.status"/></Link></div><p v-else class="px-4 py-10 text-center text-sm text-sch-muted sm:px-5">Belum ada project.</p></AdminCard></div>
            <div class="min-w-0 space-y-5"><AdminCard class="p-5"><h2 class="font-bold text-sch-navy">Quick Actions</h2><div class="mt-4 grid gap-2"><AdminButton href="/admin/services/create" icon="plus" class="w-full">Tambah Layanan</AdminButton><AdminButton href="/admin/projects/create" variant="secondary" icon="plus" class="w-full">Tambah Project</AdminButton><AdminButton href="/admin/articles/create" variant="secondary" icon="plus" class="w-full">Tulis Artikel</AdminButton></div></AdminCard><AdminCard><header class="flex min-w-0 items-center justify-between gap-3 border-b border-sch-border px-4 py-4 sm:px-5"><h2 class="min-w-0 font-bold text-sch-navy">Latest Articles</h2><Link href="/admin/articles" class="shrink-0 text-sm font-semibold text-sch-blue hover:underline">Semua</Link></header><div v-if="latestArticles.length" class="divide-y divide-sch-border"><Link v-for="article in latestArticles" :key="article.id" :href="`/admin/articles/${article.slug}/edit`" class="block min-w-0 px-4 py-4 hover:bg-slate-50 sm:px-5"><div class="flex min-w-0 flex-col items-start gap-2 sm:flex-row sm:justify-between sm:gap-3"><p class="min-w-0 break-words text-sm font-semibold text-sch-navy sm:truncate">{{ article.title }}</p><StatusBadge :value="article.status"/></div><p class="mt-1 break-words text-xs leading-5 text-sch-muted">{{ article.category?.name || 'Tanpa kategori' }} · {{ date(article.updated_at) }}</p></Link></div><p v-else class="px-4 py-10 text-center text-sm text-sch-muted sm:px-5">Belum ada artikel.</p></AdminCard></div>
        </div>
    </AdminLayout>
</template>
