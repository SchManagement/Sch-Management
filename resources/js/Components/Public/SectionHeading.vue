<script setup>
import PublicBadge from './PublicBadge.vue';
defineProps({ eyebrow: String, title: String, description: String, align: { type: String, default: 'left' }, dark: Boolean, level: { type: String, default: 'h2' }, headingId: String });
</script>
<template><div :class="[align === 'center' ? 'mx-auto max-w-3xl text-center' : 'max-w-3xl', dark ? 'text-white' : 'text-sch-ink']"><PublicBadge v-if="eyebrow" :dark="dark">{{ eyebrow }}</PublicBadge><component :is="level" :id="headingId" class="public-heading mt-4">{{ title }}</component><p v-if="description" class="public-body-lg mt-5" :class="dark ? 'text-slate-300' : 'text-sch-muted'">{{ description }}</p></div></template>
