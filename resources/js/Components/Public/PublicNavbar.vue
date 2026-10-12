<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import BrandMark from './BrandMark.vue';
import MobileNavigation from './MobileNavigation.vue';
import PublicButton from './PublicButton.vue';
import PublicIcon from './PublicIcon.vue';
import SmartLink from './SmartLink.vue';

const props = defineProps({ transparentAtTop: Boolean });
const page = usePage();
const mobileOpen = ref(false);
const scrolled = ref(false);
let ticking = false;
let animationFrame = null;

const fallback = [
    { label: 'Beranda', url: '/' }, { label: 'Tentang', url: '/about' }, { label: 'Layanan', url: '/services' },
    { label: 'Portfolio', url: '/portfolio' }, { label: 'Insight', url: '/insights' }, { label: 'Kontak', url: '/contact' },
];
const brand = computed(() => page.props.publicSite?.brand || { name: 'SCH Management' });
const navigation = computed(() => {
    const cmsItems = page.props.publicSite?.navigation?.header || [];
    return cmsItems.length ? cmsItems : fallback;
});
const desktopItems = computed(() => navigation.value.flatMap(item => item.url ? [item] : (item.children || [])));
const cta = { label: 'Diskusikan Project', href: '/request-project' };
const transparent = computed(() => props.transparentAtTop && !scrolled.value && !mobileOpen.value);
const currentPath = computed(() => `/${page.url.split('?')[0].split('/').filter(Boolean).join('/')}`);
const isActive = item => {
    if (!item.url || /^(https?:|mailto:|tel:)/i.test(item.url)) return false;
    const path = `/${item.url.split('?')[0].split('#')[0].split('/').filter(Boolean).join('/')}`;
    return path === '/' ? currentPath.value === '/' : currentPath.value === path || currentPath.value.startsWith(`${path}/`);
};
const updateScroll = () => {
    if (ticking) return;
    ticking = true;
    animationFrame = window.requestAnimationFrame(() => { scrolled.value = window.scrollY > 24; ticking = false; animationFrame = null; });
};
onMounted(() => { updateScroll(); window.addEventListener('scroll', updateScroll, { passive: true }); });
onBeforeUnmount(() => { window.removeEventListener('scroll', updateScroll); if (animationFrame !== null) window.cancelAnimationFrame(animationFrame); });
watch(() => page.url, () => { mobileOpen.value = false; });
</script>

<template>
    <header class="fixed inset-x-0 top-0 z-50 transition-[background-color,border-color,box-shadow] duration-200" :class="transparent ? 'border-b border-transparent bg-transparent' : 'border-b border-sch-border/80 bg-white/95 shadow-sch-sm backdrop-blur-xl'">
        <div class="public-container flex h-20 items-center justify-between gap-6">
            <SmartLink href="/" class="min-w-0 max-w-[calc(100%-4rem)] rounded-sch-sm lg:max-w-none lg:shrink-0" aria-label="SCH Management — Beranda">
                <BrandMark :name="brand.name" logo="/images/brand/sch-logo-horizontal.png" horizontal :light="transparent"/>
            </SmartLink>
            <nav aria-label="Navigasi utama" class="hidden min-w-0 flex-1 justify-center lg:flex"><ul class="flex items-center gap-1"><li v-for="item in desktopItems" :key="item.label"><SmartLink :href="item.url" :target="item.target" class="relative inline-flex min-h-12 items-center rounded-sch-sm px-3 text-sm font-semibold transition-colors after:absolute after:inset-x-3 after:bottom-1 after:h-0.5 after:origin-left after:scale-x-0 after:rounded-full after:bg-sch-blue after:transition-transform" :class="[transparent ? 'text-slate-200 hover:text-white' : 'text-sch-muted hover:text-sch-navy', isActive(item) ? (transparent ? 'text-white after:scale-x-100' : 'text-sch-navy after:scale-x-100') : 'hover:after:scale-x-100']" :aria-current="isActive(item) ? 'page' : undefined">{{ item.label }}</SmartLink></li></ul></nav>
            <div class="hidden shrink-0 lg:block"><PublicButton :href="cta.href" arrow>{{ cta.label }}</PublicButton></div>
            <button type="button" class="grid size-12 shrink-0 place-items-center rounded-sch-md transition-colors lg:hidden" :class="transparent ? 'text-white hover:bg-white/10' : 'text-sch-navy hover:bg-slate-100'" aria-label="Buka menu navigasi" :aria-expanded="mobileOpen" aria-controls="public-mobile-navigation" @click="mobileOpen = true"><PublicIcon name="menu" class="size-6"/></button>
        </div>
    </header>
    <MobileNavigation id="public-mobile-navigation" :open="mobileOpen" :items="navigation" :brand="brand" :cta="cta" @close="mobileOpen = false"/>
</template>
