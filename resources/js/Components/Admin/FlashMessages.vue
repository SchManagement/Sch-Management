<script setup>
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AdminIcon from './AdminIcon.vue';

const page = usePage();
const dismissed = ref([]);
const messages = computed(() => Object.entries(page.props.flash || {}).filter(([key, value]) => value && !dismissed.value.includes(`${key}:${value}`)));
watch(() => page.url, () => { dismissed.value = []; });
const style = { success: 'border-emerald-200 bg-emerald-50 text-emerald-800', error: 'border-red-200 bg-red-50 text-red-800', warning: 'border-amber-200 bg-amber-50 text-amber-900', info: 'border-blue-200 bg-blue-50 text-blue-800' };
const icon = { success: 'check', error: 'warning', warning: 'warning', info: 'info' };
</script>
<template><div aria-live="polite" aria-atomic="true" class="fixed right-4 top-20 z-[70] flex w-[calc(100%-2rem)] max-w-md flex-col gap-2"><div v-for="([type, message]) in messages" :key="`${type}:${message}`" class="flex items-start gap-3 rounded-xl border p-4 shadow-lg" :class="style[type]"><AdminIcon :name="icon[type]" class="mt-0.5 size-5 shrink-0"/><p class="min-w-0 flex-1 text-sm font-medium leading-5">{{ message }}</p><button type="button" class="grid size-11 shrink-0 place-items-center rounded-lg hover:bg-black/5" aria-label="Tutup pemberitahuan" @click="dismissed.push(`${type}:${message}`)"><AdminIcon name="close" class="size-4"/></button></div></div></template>
