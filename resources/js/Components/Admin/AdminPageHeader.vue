<script setup>
import AdminButton from './AdminButton.vue';
defineProps({ title: String, description: String, actionLabel: String, actionUrl: String });
</script>
<template>
    <header class="mb-7 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0"><nav aria-label="Breadcrumb" class="mb-2 text-xs font-semibold uppercase tracking-[0.12em] text-sch-blue"><a href="/admin" class="rounded hover:underline">Admin</a><span aria-hidden="true"> / </span><span class="text-sch-muted">{{ title }}</span></nav><h1 class="text-2xl font-bold tracking-[-0.025em] text-sch-navy sm:text-3xl">{{ title }}</h1><p v-if="description" class="mt-2 max-w-3xl text-sm leading-6 text-sch-muted">{{ description }}</p></div>
        <AdminButton v-if="actionUrl" :href="actionUrl" icon="plus" class="shrink-0">{{ actionLabel }}</AdminButton><slot name="action" />
    </header>
</template>
