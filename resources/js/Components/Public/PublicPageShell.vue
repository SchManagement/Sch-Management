<script setup>
import Breadcrumb from './Breadcrumb.vue';
defineProps({ eyebrow: String, title: String, description: String, breadcrumbs: { type: Array, default: () => [] }, dark: Boolean });
</script>
<template><section class="border-b border-sch-border" :class="dark ? 'bg-sch-navy text-white' : 'bg-white text-sch-ink'"><div class="public-container py-14 sm:py-20 lg:py-24"><Breadcrumb v-if="breadcrumbs.length" :items="breadcrumbs" :dark="dark"/><p v-if="eyebrow" class="mt-8 text-xs font-bold uppercase tracking-[0.16em]" :class="dark ? 'text-cyan-300' : 'text-sch-blue'">{{ eyebrow }}</p><h1 class="public-heading mt-3 max-w-4xl">{{ title }}</h1><p v-if="description" class="public-body-lg mt-5 max-w-3xl" :class="dark ? 'text-slate-300' : 'text-sch-muted'">{{ description }}</p><div v-if="$slots.default" class="mt-8"><slot/></div></div></section></template>
