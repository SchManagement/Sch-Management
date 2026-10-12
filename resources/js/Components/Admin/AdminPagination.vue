<script setup>
import { Link } from '@inertiajs/vue3';
const props = defineProps({ links: { type: Array, default: () => [] }, from: Number, to: Number, total: Number });
const label = (item, index) => {
    if (index === 0) return 'Sebelumnya';
    if (index === props.links.length - 1) return 'Berikutnya';
    return String(item.label).replace(/<[^>]*>/g, '').replace(/&[^;]+;/g, '').trim();
};
</script>
<template><nav v-if="links.length > 3" aria-label="Paginasi" class="flex flex-col gap-4 border-t border-sch-border px-5 py-4 sm:flex-row sm:items-center sm:justify-between"><p class="text-sm text-sch-muted">Menampilkan {{ from }}–{{ to }} dari {{ total }}</p><div class="flex flex-wrap gap-1"><template v-for="(link, index) in links" :key="index"><Link v-if="link.url" :href="link.url" preserve-scroll class="grid min-h-11 min-w-11 place-items-center rounded-lg px-3 text-sm font-semibold transition-colors" :class="link.active ? 'bg-sch-navy text-white' : 'text-sch-muted hover:bg-slate-100 hover:text-sch-navy'" :aria-current="link.active ? 'page' : undefined" :aria-label="index === 0 || index === links.length - 1 ? `${label(link, index)} halaman` : `Halaman ${label(link, index)}`">{{ label(link, index) }}</Link><span v-else class="grid min-h-11 min-w-11 place-items-center px-2 text-sm text-slate-400" aria-disabled="true">{{ label(link, index) }}</span></template></div></nav></template>
