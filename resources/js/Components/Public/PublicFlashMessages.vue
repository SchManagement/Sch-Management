<script setup>
import { computed, onBeforeUnmount, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import PublicIcon from './PublicIcon.vue';

const page = usePage();
const hidden = ref([]);
let timer = null;
const messages = computed(() => Object.entries(page.props.flash || {}).filter(([type, value]) => value && !hidden.value.includes(`${type}:${value}`)));
const tones = { success: 'border-emerald-200 bg-emerald-50 text-emerald-800', error: 'border-red-200 bg-red-50 text-red-800', warning: 'border-amber-200 bg-amber-50 text-amber-900', info: 'border-blue-200 bg-blue-50 text-blue-800' };
const icons = { success: 'check', error: 'alert', warning: 'alert', info: 'info' };
watch(() => page.url, () => {
    hidden.value = [];
    if (typeof window === 'undefined') return;
    if (timer !== null) window.clearTimeout(timer);
    timer = window.setTimeout(() => { hidden.value = messages.value.map(([type, value]) => `${type}:${value}`); }, 5000);
}, { immediate: true });
onBeforeUnmount(() => { if (timer !== null) window.clearTimeout(timer); });
</script>
<template><div class="pointer-events-none fixed inset-x-4 top-24 z-[70] ml-auto flex max-w-md flex-col gap-2" aria-live="polite" aria-atomic="true"><div v-for="([type, message]) in messages" :key="`${type}:${message}`" class="pointer-events-auto flex items-start gap-3 rounded-sch-md border p-4 shadow-sch-md" :class="tones[type]"><PublicIcon :name="icons[type]" class="mt-0.5 size-5 shrink-0"/><p class="min-w-0 flex-1 text-sm font-semibold leading-6">{{ message }}</p><button type="button" class="grid size-11 shrink-0 place-items-center rounded-sch-sm hover:bg-black/5" aria-label="Tutup pemberitahuan" @click="hidden.push(`${type}:${message}`)"><PublicIcon name="close" class="size-4"/></button></div></div></template>
