<script setup>
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import BrandMark from './BrandMark.vue';
import PublicButton from './PublicButton.vue';
import PublicIcon from './PublicIcon.vue';
import SmartLink from './SmartLink.vue';

const props = defineProps({ id: String, open: Boolean, items: Array, brand: Object, cta: Object });
const emit = defineEmits(['close']);
const page = usePage();
const panel = ref(null);
const closeButton = ref(null);
let previousFocus = null;
let previousBodyOverflow = '';
const currentPath = computed(() => `/${page.url.split('?')[0].split('/').filter(Boolean).join('/')}`);
const isActive = item => {
    if (!item.url || /^(https?:|mailto:|tel:)/i.test(item.url)) return false;
    const path = `/${item.url.split('?')[0].split('#')[0].split('/').filter(Boolean).join('/')}`;
    return path === '/' ? currentPath.value === '/' : currentPath.value === path || currentPath.value.startsWith(`${path}/`);
};

const focusable = () => panel.value ? [...panel.value.querySelectorAll('a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])')] : [];
const onKeydown = event => {
    if (event.key === 'Escape') { emit('close'); return; }
    if (event.key !== 'Tab') return;
    const elements = focusable();
    if (!elements.length) return;
    const first = elements[0]; const last = elements[elements.length - 1];
    if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
    if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
};

watch(() => props.open, async value => {
    if (value) {
        previousFocus = document.activeElement;
        previousBodyOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        await nextTick();
        closeButton.value?.focus();
    } else {
        document.body.style.overflow = previousBodyOverflow;
        await nextTick();
        previousFocus?.focus?.();
    }
});
onBeforeUnmount(() => { document.body.style.overflow = previousBodyOverflow; });
</script>

<template><Teleport to="body"><Transition name="mobile-menu"><div v-if="open" class="fixed inset-0 z-[80] lg:hidden" @keydown="onKeydown"><button type="button" class="absolute inset-0 bg-sch-midnight/65 backdrop-blur-[2px]" aria-label="Tutup menu navigasi" tabindex="-1" @click="emit('close')"/><aside :id="id" ref="panel" role="dialog" aria-modal="true" aria-labelledby="mobile-menu-title" class="absolute inset-y-0 right-0 flex w-[min(88vw,24rem)] flex-col bg-white shadow-2xl"><div class="flex min-h-20 items-center justify-between gap-4 border-b border-sch-border px-5"><h2 id="mobile-menu-title" class="sr-only">Navigasi utama</h2><BrandMark :name="brand.name" logo="/images/brand/sch-logo-horizontal.png" horizontal/><button ref="closeButton" type="button" class="grid size-12 place-items-center rounded-sch-md text-sch-navy hover:bg-slate-100" aria-label="Tutup menu" @click="emit('close')"><PublicIcon name="close" class="size-6"/></button></div><nav aria-label="Navigasi seluler" class="min-h-0 flex-1 overflow-y-auto px-5 py-6"><ul class="space-y-1"><li v-for="item in items" :key="item.label"><SmartLink v-if="item.url" :href="item.url" :target="item.target" class="flex min-h-12 items-center justify-between rounded-sch-md px-3 text-base font-bold transition-colors hover:bg-sch-soft-blue hover:text-sch-blue" :class="isActive(item) ? 'bg-sch-soft-blue text-sch-blue' : 'text-sch-navy'" :aria-current="isActive(item) ? 'page' : undefined" @click="emit('close')"><span>{{ item.label }}</span><PublicIcon name="chevron-right" class="size-4"/></SmartLink><span v-else class="block px-3 py-3 text-sm font-bold uppercase tracking-[0.12em] text-sch-muted">{{ item.label }}</span><ul v-if="item.children?.length" class="ml-4 border-l border-sch-border pl-3"><li v-for="child in item.children" :key="child.label"><SmartLink :href="child.url" :target="child.target" class="flex min-h-12 items-center rounded-sch-md px-3 text-sm font-semibold hover:bg-slate-50 hover:text-sch-blue" :class="isActive(child) ? 'text-sch-blue' : 'text-sch-muted'" :aria-current="isActive(child) ? 'page' : undefined" @click="emit('close')">{{ child.label }}</SmartLink></li></ul></li></ul></nav><div class="border-t border-sch-border p-5 pb-[max(1.25rem,env(safe-area-inset-bottom))]"><PublicButton :href="cta.href" class="w-full" arrow @click="emit('close')">{{ cta.label }}</PublicButton></div></aside></div></Transition></Teleport></template>

<style scoped>
a[aria-current="page"] { color: var(--color-sch-navy); }
@media (prefers-reduced-motion: reduce) {
    .mobile-menu-enter-active, .mobile-menu-leave-active,
    .mobile-menu-enter-active aside, .mobile-menu-leave-active aside { transition-duration: 0.01ms; }
}
.mobile-menu-enter-active, .mobile-menu-leave-active { transition: opacity var(--sch-duration-base) var(--ease-sch-out); }
.mobile-menu-enter-active aside, .mobile-menu-leave-active aside { transition: transform var(--sch-duration-slow) var(--ease-sch-out); }
.mobile-menu-enter-from, .mobile-menu-leave-to { opacity: 0; }
.mobile-menu-enter-from aside, .mobile-menu-leave-to aside { transform: translateX(100%); }
</style>
