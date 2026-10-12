<script setup>
import { computed, ref } from 'vue';
import PublicButton from '../Public/PublicButton.vue';
import PublicIcon from '../Public/PublicIcon.vue';
import HomeHeroVisual3D from './HomeHeroVisual3D.vue';
import { contentText, contentUrl } from '../../Utils/homepageContent';

const props = defineProps({ section: { type: Object, required: true } });
const threeReady = ref(false);
const content = computed(() => props.section.content || {});
const heading = computed(() => contentText(props.section.heading, 'Membangun Sistem Digital untuk Pertumbuhan Bisnis'));
const description = computed(() => contentText(
    content.value.description,
    'SCH Management membantu bisnis membangun website, sistem digital, dan solusi teknologi yang modern, efektif, profesional, dan siap berkembang.',
));
const primaryLabel = computed(() => contentText(content.value.primary_cta_label, 'Diskusikan Project'));
const primaryUrl = computed(() => contentUrl(content.value.primary_cta_url, '/request-project'));
const secondaryLabel = computed(() => contentText(content.value.secondary_cta_label, 'Lihat Portfolio'));
const secondaryUrl = computed(() => contentUrl(content.value.secondary_cta_url, '/portfolio'));
const defaultHighlight = 'Tumbuh Lebih Jauh.';
const requestedHighlight = computed(() => contentText(content.value.highlight, ''));
const headingParts = computed(() => {
    const words = heading.value.trim().split(/\s+/);
    const editorialHighlight = words.length > 4 ? words.slice(-2).join(' ') : '';
    const highlight = requestedHighlight.value || (heading.value.includes(defaultHighlight) ? defaultHighlight : editorialHighlight);

    if (!highlight || !heading.value.includes(highlight)) {
        return { lead: heading.value, highlight: '' };
    }

    return { lead: heading.value.replace(highlight, '').trim(), highlight };
});
</script>

<template>
    <section class="home-hero relative isolate overflow-hidden bg-sch-midnight text-white" aria-labelledby="home-hero-title" data-home-section="hero">
        <div class="hero-light pointer-events-none absolute -right-[22rem] -top-[24rem] -z-20 size-[58rem]" aria-hidden="true"/>
        <div class="hero-horizon pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-40" aria-hidden="true"/>

        <div class="public-container-wide relative grid gap-4 pb-5 pt-24 sm:min-h-[46rem] sm:gap-12 sm:pb-20 sm:pt-36 md:grid-cols-12 md:items-center md:gap-x-8 md:gap-y-10 lg:pb-24 lg:pt-36 xl:min-h-[50rem]">
            <div class="relative z-10 md:col-span-7" data-reveal="hero-copy">
                <h1 id="home-hero-title" class="hero-title max-w-[16ch]" data-hero-heading>
                    <span>{{ headingParts.lead }}</span>
                    <span v-if="headingParts.highlight" class="hero-title-accent block">{{ headingParts.highlight }}</span>
                </h1>

                <div class="mt-5 max-w-3xl sm:mt-8">
                    <p class="max-w-[42rem] text-base leading-7 text-slate-300 sm:text-lg sm:leading-8" data-hero-description>{{ description }}</p>
                </div>

                <div class="mt-6 flex flex-col gap-3 sm:mt-9 sm:flex-row sm:flex-wrap" data-hero-actions>
                    <PublicButton :href="primaryUrl" arrow>{{ primaryLabel }}</PublicButton>
                    <PublicButton :href="secondaryUrl" variant="inverted">{{ secondaryLabel }}</PublicButton>
                </div>

                <ul class="hero-trust-strip relative mt-7 grid max-w-[38rem] gap-y-3 pt-6 sm:grid-cols-3 sm:gap-y-0" aria-label="Nilai utama SCH Management" data-hero-support>
                    <li class="flex items-center gap-3 sm:pr-4">
                        <PublicIcon name="layers" class="size-5 shrink-0 text-cyan-300"/>
                        <span class="text-sm font-semibold leading-5 text-slate-200">Solusi Terstruktur</span>
                    </li>
                    <li class="flex items-center gap-3 sm:border-l sm:border-white/10 sm:px-4">
                        <PublicIcon name="shield-check" class="size-5 shrink-0 text-cyan-300"/>
                        <span class="text-sm font-semibold leading-5 text-slate-200">Aman &amp; Terpercaya</span>
                    </li>
                    <li class="flex items-center gap-3 sm:border-l sm:border-white/10 sm:pl-4">
                        <PublicIcon name="users" class="size-5 shrink-0 text-cyan-300"/>
                        <span class="text-sm font-semibold leading-5 text-slate-200">Partner Jangka Panjang</span>
                    </li>
                </ul>
            </div>

            <div class="relative mx-auto w-full max-w-[34rem] md:-top-2 md:left-3 md:col-span-5 md:mx-0 md:ml-auto xl:left-5 xl:max-w-[38rem]" data-hero-visual data-reveal="hero-visual">
                <div class="hero-art relative aspect-[1/1.03]" aria-hidden="true">
                    <img :src="'/images/brand/sch-logo-3d-reference.png'" alt="" width="1254" height="1254" decoding="async"
                        class="hero-art-static pointer-events-none absolute inset-[18%] size-[64%] translate-x-[4%] -translate-y-[2%] object-contain"
                        :class="{ 'hero-art-static--muted': threeReady }"/>

                    <HomeHeroVisual3D @ready="threeReady = true" @fallback="threeReady = false"/>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.home-hero {
    background-image: linear-gradient(90deg, rgb(6 16 31 / 0.78), rgb(6 16 31 / 0.5) 47%, rgb(6 16 31 / 0.28)),
        url('/images/style-b-backgrounds/01-home-hero-background.webp');
    background-position: center, 58% center;
    background-size: cover;
    background-repeat: no-repeat;
}
.hero-light { background: radial-gradient(circle, color-mix(in srgb, var(--color-sch-blue) 24%, transparent), transparent 66%); }
.hero-horizon { background: linear-gradient(to bottom, transparent, color-mix(in srgb, var(--color-sch-dark-blue) 55%, transparent)); }

.hero-title {
    font-size: clamp(3rem, 5.2vw, 5.4rem);
    font-weight: 720;
    line-height: 0.98;
    letter-spacing: -0.058em;
    text-wrap: balance;
}

.hero-title-accent {
    color: transparent;
    background: linear-gradient(100deg, var(--color-sch-electric), var(--color-sch-cyan));
    background-clip: text;
    -webkit-background-clip: text;
}

.hero-art {
    color: var(--color-sch-cyan);
    filter: drop-shadow(0 28px 60px rgb(0 8 24 / 0.34));
}

.hero-art-static--muted { opacity: 0; transition: opacity 500ms var(--ease-sch-out); }

.hero-trust-strip::before {
    position: absolute;
    top: 0;
    left: 0;
    width: min(68%, 28rem);
    height: 1px;
    content: '';
    background: linear-gradient(90deg, rgb(255 255 255 / 0.12), rgb(255 255 255 / 0.025) 78%, transparent);
}

@media (max-width: 639px) {
    .home-hero { background-position: center, 25% center; }
    .hero-title { max-width: 16ch; font-size: clamp(2.25rem, 10vw, 2.625rem); letter-spacing: -0.045em; line-height: 1.02; }
    .hero-art { margin-inline: auto; max-width: 11rem; }
}

@media (prefers-reduced-motion: reduce) {
    .hero-art-static { transition: none; }
}
</style>
