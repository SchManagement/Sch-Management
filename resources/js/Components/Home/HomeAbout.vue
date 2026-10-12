<script setup>
import { computed } from 'vue';
import PublicBadge from '../Public/PublicBadge.vue';
import PublicButton from '../Public/PublicButton.vue';
import { contentItems, contentText, contentUrl } from '../../Utils/homepageContent';

const props = defineProps({ section: { type: Object, required: true } });
const content = computed(() => props.section.content || {});
const heading = computed(() => contentText(props.section.heading, 'Teknologi yang Dibangun untuk Membantu Bisnis Bergerak Lebih Maju.'));
const description = computed(() => contentText(
    content.value.description,
    'SCH Management berfokus pada pengembangan website profesional, aplikasi web, dan solusi IT yang disusun berdasarkan kebutuhan nyata bisnis—bukan sekadar mengikuti tren.',
));
const secondaryDescription = computed(() => contentText(
    content.value.secondary_description,
    'Setiap solusi dirancang dengan fondasi yang terstruktur, pengalaman pengguna yang jelas, dan ruang untuk berkembang seiring perubahan kebutuhan.',
));
const points = computed(() => contentItems(content.value.points, [
    { title: 'Berorientasi pada kebutuhan', description: 'Teknologi dipilih dan disusun untuk mendukung tujuan bisnis yang jelas.' },
    { title: 'Terstruktur sejak awal', description: 'Fondasi yang rapi membantu produk digital tetap mudah dikelola dan dikembangkan.' },
    { title: 'Dibangun untuk bertumbuh', description: 'Arsitektur disiapkan agar dapat mengikuti kebutuhan bisnis yang terus bergerak.' },
], 3).map((point, index) => point && typeof point === 'object' ? point : {
    title: typeof point === 'string' ? point : `Prinsip ${index + 1}`,
    description: '',
}));
const ctaLabel = computed(() => contentText(content.value.cta_label, 'Tentang SCH Management'));
const ctaUrl = computed(() => contentUrl(content.value.cta_url, '/about'));
</script>

<template>
    <section class="relative overflow-hidden bg-white py-12 sm:py-16 lg:py-20" aria-labelledby="home-about-title" data-home-section="about">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-sch-border to-transparent" aria-hidden="true"/>
        <div class="public-container">
            <div class="grid gap-10 lg:grid-cols-12 lg:gap-x-8">
                <div class="lg:col-span-4" data-reveal>
                    <PublicBadge>{{ contentText(content.eyebrow, 'TENTANG SCH MANAGEMENT') }}</PublicBadge>
                    <div class="mt-10 hidden items-end gap-4 lg:flex" aria-hidden="true">
                        <span class="font-mono text-[0.625rem] uppercase tracking-[0.2em] text-slate-400">Purpose / Direction</span>
                        <span class="mb-1 h-px flex-1 bg-sch-border"/>
                    </div>
                </div>

                <div class="lg:col-span-8 lg:pl-8" data-reveal="about-copy">
                    <h2 id="home-about-title" class="about-title max-w-[19ch] text-sch-ink">{{ heading }}</h2>
                    <div class="mt-8 grid gap-5 border-l-2 border-sch-blue pl-5 text-base leading-8 text-sch-muted sm:grid-cols-2 sm:gap-8 sm:pl-7">
                        <p>{{ description }}</p>
                        <p>{{ secondaryDescription }}</p>
                    </div>
                    <PublicButton :href="ctaUrl" variant="outline" arrow class="mt-9">{{ ctaLabel }}</PublicButton>
                </div>
            </div>

            <div class="sch-home-about-visual mt-12 grid border-y border-sch-border lg:mt-16 lg:grid-cols-[0.8fr_1.2fr]">
                <div class="sch-story-art grid min-h-64 place-items-center bg-sch-navy py-10 lg:min-h-[28rem]" aria-hidden="true">
                </div>

                <ol class="divide-y divide-sch-border lg:pl-12" data-stagger="about-points">
                    <li v-for="(point, index) in points" :key="`${point.title}-${index}`" class="group relative grid gap-2 py-7 sm:grid-cols-[3rem_1fr] sm:gap-x-6 lg:py-9">
                        <span class="font-mono text-xs font-bold tracking-[0.14em] text-sch-blue" aria-hidden="true">{{ index + 1 }}</span>
                        <div><h3 class="text-lg font-semibold tracking-[-0.02em] text-sch-navy">{{ contentText(point.title, `Prinsip ${index + 1}`) }}</h3>
                        <p v-if="contentText(point.description, '')" class="mt-2 text-sm leading-7 text-sch-muted">{{ contentText(point.description, '') }}</p></div>
                    </li>
                </ol>
            </div>
        </div>
    </section>
</template>

<style scoped>
.about-title {
    font-size: clamp(2.25rem, 4.5vw, 4.75rem);
    font-weight: 660;
    line-height: 1.05;
    letter-spacing: -0.045em;
}
</style>
