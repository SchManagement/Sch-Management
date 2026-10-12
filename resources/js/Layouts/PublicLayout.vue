<script setup>
import { computed, nextTick, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import PublicFooter from '../Components/Public/PublicFooter.vue';
import PublicFlashMessages from '../Components/Public/PublicFlashMessages.vue';
import PublicNavbar from '../Components/Public/PublicNavbar.vue';
import SeoHead from '../Components/Public/SeoHead.vue';
import GoogleAnalytics from '../Components/Public/GoogleAnalytics.vue';
import CookieConsent from '../Components/Public/CookieConsent.vue';
import { introPending, usePublicMotion } from '../Composables/usePublicMotion';

const props = defineProps({ headerMode: { type: String, default: 'solid' } });
const page = usePage();
const main = ref(null);
const loader = ref(null);
const showLoader = ref(introPending());
const analyticsAvailable = computed(() => /^G-[A-Z0-9]+$/.test(page.props.seo?.analyticsId || ''));
const analyticsAllowed = ref(false);
const cookieSettingsOpen = ref(false);

usePublicMotion(main, loader, showLoader, page);

watch(() => page.url, async () => {
    await nextTick();
    main.value?.focus({ preventScroll: true });
});
</script>

<template>
    <div class="public-site min-h-dvh bg-white text-sch-ink">
        <div v-if="showLoader" ref="loader" class="motion-loader pointer-events-none fixed inset-0 z-[90] grid place-items-center overflow-hidden bg-sch-midnight text-white" aria-hidden="true">
            <div class="relative grid justify-items-center gap-4 px-6">
                <div class="relative size-36 sm:size-44" data-loader-icon>
                    <svg viewBox="0 0 1254 1254" class="absolute inset-0 size-full" aria-hidden="true">
                        <defs>
                            <clipPath id="sch-loader-top"><rect x="0" y="0" width="1254" height="440"/></clipPath>
                            <clipPath id="sch-loader-middle"><rect x="0" y="440" width="900" height="400"/></clipPath>
                            <clipPath id="sch-loader-lower"><rect x="0" y="840" width="900" height="414"/></clipPath>
                            <clipPath id="sch-loader-right"><rect x="900" y="440" width="354" height="814"/></clipPath>
                        </defs>
                        <g data-loader-piece clip-path="url(#sch-loader-top)"><image :href="'/images/brand/sch-icon.png'" width="1254" height="1254"/></g>
                        <g data-loader-piece clip-path="url(#sch-loader-middle)"><image :href="'/images/brand/sch-icon.png'" width="1254" height="1254"/></g>
                        <g data-loader-piece clip-path="url(#sch-loader-lower)"><image :href="'/images/brand/sch-icon.png'" width="1254" height="1254"/></g>
                        <g data-loader-piece clip-path="url(#sch-loader-right)"><image :href="'/images/brand/sch-icon.png'" width="1254" height="1254"/></g>
                    </svg>
                    <img :src="'/images/brand/sch-icon.png'" alt="" width="1254" height="1254" class="absolute inset-0 size-full object-contain" data-loader-lock/>
                    <span class="motion-loader-shine absolute inset-0 overflow-hidden" :style="{ maskImage: 'url(/images/brand/sch-icon.png)' }"><span class="absolute inset-0" data-loader-sweep/></span>
                </div>
                <span class="text-[0.625rem] font-semibold uppercase tracking-[0.24em] text-slate-300">SCH Management</span>
            </div>
        </div>
        <SeoHead v-if="page.props.seo" :seo="page.props.seo"/>
        <GoogleAnalytics v-if="analyticsAvailable && analyticsAllowed" :measurement-id="page.props.seo.analyticsId"/>
        <a href="#public-main-content" class="fixed left-4 top-4 z-[100] -translate-y-24 rounded-sch-md bg-white px-4 py-3 text-sm font-bold text-sch-navy shadow-sch-md transition-transform focus:translate-y-0">Lewati ke konten utama</a>
        <PublicFlashMessages/>
        <PublicNavbar :transparent-at-top="props.headerMode === 'overlay'"/>
        <main id="public-main-content" ref="main" tabindex="-1" :class="props.headerMode === 'overlay' ? '' : 'pt-20'"><slot/></main>
        <PublicFooter :cookie-settings-available="analyticsAvailable" @open-cookie-settings="cookieSettingsOpen = true"/>
        <CookieConsent v-if="analyticsAvailable" v-model:open="cookieSettingsOpen" @analytics-change="analyticsAllowed = $event"/>
    </div>
</template>

<style scoped>
[data-loader-lock] { opacity: 0; }
.motion-loader-shine {
    mask-size: contain;
    mask-repeat: no-repeat;
}
[data-loader-sweep] {
    background: linear-gradient(90deg, transparent 36%, rgb(0 181 255 / 0.26) 50%, transparent 64%);
    transform: translateX(-125%);
}
@media (prefers-reduced-motion: reduce) {
    [data-loader-piece], [data-loader-sweep] { display: none; }
    [data-loader-lock] { opacity: 1; }
}
</style>
