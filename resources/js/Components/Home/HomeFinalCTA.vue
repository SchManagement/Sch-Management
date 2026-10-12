<script setup>
import { computed } from 'vue';
import PublicButton from '../Public/PublicButton.vue';
import { contentText, contentUrl } from '../../Utils/homepageContent';

const props = defineProps({ section: { type: Object, required: true } });
const content = computed(() => props.section.content || {});
const heading = computed(() => contentText(props.section.heading, 'Punya Ide Digital?\nMari Kita Wujudkan.'));
</script>

<template>
    <section class="final-cta relative isolate overflow-hidden bg-sch-dark-blue py-12 text-white sm:py-16 lg:py-20" aria-labelledby="home-final-cta-title" data-home-section="final_cta">
        <div class="pointer-events-none absolute -right-48 top-1/2 -z-10 size-[38rem] -translate-y-1/2 rounded-full bg-sch-blue/15 blur-3xl" aria-hidden="true"/>

        <div class="public-container-wide">
            <div class="grid gap-10 border-y border-white/15 py-10 lg:grid-cols-12 lg:items-end lg:py-14">
                <div class="lg:col-span-2">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-300">{{ contentText(content.eyebrow, 'MULAI PROJECT ANDA') }}</p>
                    <p class="mt-6 font-mono text-[0.625rem] uppercase tracking-[0.2em] text-slate-500" aria-hidden="true">Next / 1</p>
                </div>

                <div class="final-cta-headline lg:col-span-7" data-reveal>
                    <h2 id="home-final-cta-title" class="cta-title max-w-[15ch] whitespace-pre-line">{{ heading }}</h2>
                    <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                        {{ contentText(content.description, 'Ceritakan kebutuhan dan tujuan bisnis Anda. Kami siap membantu menyusun langkah digital yang tepat, jelas, dan dapat dikembangkan.') }}
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row lg:col-span-3 lg:flex-col lg:items-stretch" data-reveal="cta-actions">
                    <PublicButton :href="contentUrl(content.primary_cta_url, '/request-project')" arrow>
                        {{ contentText(content.primary_cta_label, 'Diskusikan Project') }}
                    </PublicButton>
                    <PublicButton :href="contentUrl(content.secondary_cta_url, '/contact')" variant="inverted">
                        {{ contentText(content.secondary_cta_label, 'Hubungi Kami') }}
                    </PublicButton>
                </div>
            </div>

            <div class="mt-7 flex items-center justify-between gap-5 text-[0.625rem] font-bold uppercase tracking-[0.2em] text-slate-500" aria-hidden="true">
                <span>Ideas into digital systems</span>
                <span class="hidden h-px flex-1 bg-white/10 sm:block"/>
                <span>SCH Management</span>
            </div>
        </div>
    </section>
</template>

<style scoped>
.final-cta-headline {
    position: relative;
    isolation: isolate;
}

.final-cta-headline::before {
    content: '';
    position: absolute;
    inset: -38% -12% -44% -22%;
    z-index: -1;
    pointer-events: none;
    background:
        radial-gradient(ellipse at 44% 48%, rgb(0 108 255 / 0.22), rgb(0 108 255 / 0.08) 38%, transparent 70%),
        radial-gradient(ellipse at 30% 56%, rgb(10 42 88 / 0.38), transparent 68%);
    filter: blur(3.75rem);
    opacity: 0.78;
}

.cta-title {
    font-size: clamp(2.5rem, 5.5vw, 5.5rem);
    font-weight: 660;
    line-height: 1;
    letter-spacing: -0.05em;
}

@media (max-width: 63.999rem) {
    .final-cta-headline::before {
        inset: -30% -8% -38% -18%;
        filter: blur(3.25rem);
        opacity: 0.7;
    }
}

@media (max-width: 39.999rem) {
    .final-cta-headline::before {
        inset: -24% -10% -32% -26%;
        filter: blur(2.75rem);
        opacity: 0.62;
    }
}
</style>
