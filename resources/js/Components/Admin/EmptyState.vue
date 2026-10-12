<script setup>
import AdminButton from './AdminButton.vue';
defineProps({ title: String, description: String, actionLabel: String, actionUrl: String });
</script>
<template><div class="px-6 py-14 text-center"><div class="mx-auto grid size-12 place-items-center rounded-2xl bg-sch-soft-blue text-sch-blue"><slot name="icon"><span class="text-lg font-bold" aria-hidden="true">—</span></slot></div><h2 class="mt-4 font-bold text-sch-navy">{{ title }}</h2><p v-if="description" class="mx-auto mt-2 max-w-md text-sm leading-6 text-sch-muted">{{ description }}</p><AdminButton v-if="actionUrl" :href="actionUrl" icon="plus" class="mt-5">{{ actionLabel }}</AdminButton></div></template>
