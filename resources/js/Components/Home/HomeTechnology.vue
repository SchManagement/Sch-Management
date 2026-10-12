<script setup>
import { computed } from 'vue';
import PublicBadge from '../Public/PublicBadge.vue';
import { contentText } from '../../Utils/homepageContent';

const props = defineProps({
    section: { type: Object, required: true },
    technologies: { type: Array, default: () => [] },
});
const content = computed(() => props.section.content || {});
</script>

<template>
    <section v-if="technologies.length" class="border-y border-sch-border bg-white py-14 sm:py-16 lg:py-[4.25rem]" aria-labelledby="home-technology-title" data-home-section="technology">
        <div class="public-container grid gap-9 lg:grid-cols-[minmax(0,3fr)_minmax(0,5fr)_minmax(0,1.65fr)] lg:items-center lg:gap-0">
            <div class="max-w-md lg:pr-9 xl:pr-12">
                <PublicBadge>{{ contentText(content.eyebrow, 'TEKNOLOGI KAMI') }}</PublicBadge>
                <h2 id="home-technology-title" class="mt-4 text-[clamp(1.875rem,2.5vw,2.5rem)] font-bold leading-[1.13] tracking-[-0.04em] text-sch-ink">{{ contentText(section.heading, 'Teknologi Modern untuk Solusi Anda') }}</h2>
                <p class="mt-4 text-sm leading-6 text-sch-muted sm:text-[0.9375rem] sm:leading-7">{{ contentText(content.description, 'Kami menggunakan teknologi yang relevan, stabil, dan banyak digunakan untuk mengembangkan website dan sistem digital yang aman, fleksibel, serta mudah dikembangkan.') }}</p>
            </div>

            <ul class="grid grid-cols-3 gap-x-4 gap-y-7 border-t border-sch-border pt-8 sm:grid-cols-6 sm:gap-x-2 lg:grid-cols-3 lg:gap-x-6 lg:gap-y-8 lg:border-l lg:border-t-0 lg:px-9 lg:pt-0 xl:px-12" aria-label="Teknologi yang kami gunakan">
                <li v-for="technology in technologies" :key="technology.slug" class="flex min-w-0 flex-col items-center gap-3 text-center">
                    <img :src="technology.icon" alt="" width="48" height="48" loading="lazy" decoding="async" class="h-11 w-11 object-contain sm:h-12 sm:w-12"/>
                    <span class="text-xs font-semibold leading-5 text-sch-ink sm:text-[0.8125rem]">{{ technology.name }}</span>
                </li>
            </ul>

            <div class="border-t border-sch-border pt-7 lg:self-stretch lg:border-l lg:border-t-0 lg:pl-7 lg:pt-0 xl:pl-9">
                <div class="flex h-full flex-col justify-center">
                    <p class="max-w-[12rem] text-sm font-semibold uppercase leading-[1.55] tracking-[0.08em] text-sch-ink">Teknologi yang mendukung pertumbuhan</p>
                    <span class="mt-5 block h-0.5 w-10 bg-sch-blue" aria-hidden="true"/>
                </div>
            </div>
        </div>
    </section>
</template>
