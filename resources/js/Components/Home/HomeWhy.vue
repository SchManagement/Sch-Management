<script setup>
import { computed } from 'vue';
import SectionHeading from '../Public/SectionHeading.vue';
import { contentItems, contentText } from '../../Utils/homepageContent';

const props = defineProps({ section: { type: Object, required: true } });
const content = computed(() => props.section.content || {});
const values = computed(() => contentItems(content.value.items, [
    { title: 'Strategic Development', description: 'Keputusan solusi disusun berdasarkan kebutuhan, konteks, dan tujuan bisnis.' },
    { title: 'Modern Technology', description: 'Menggunakan teknologi modern yang relevan, terstruktur, dan dapat dipelihara.' },
    { title: 'Responsive Experience', description: 'Pengalaman dirancang agar tetap jelas dan nyaman di desktop, tablet, maupun mobile.' },
    { title: 'Long-Term Support', description: 'Fondasi dikembangkan agar mudah dirawat, disesuaikan, dan diperluas dalam jangka panjang.' },
], 4).map((item, index) => item && typeof item === 'object' ? item : {
    title: typeof item === 'string' ? item : `Nilai ${index + 1}`,
    description: '',
}));
</script>

<template>
    <section class="relative bg-white py-12 sm:py-16 lg:py-20" aria-labelledby="home-why-title" data-home-section="why_sch">
        <div class="public-container grid gap-12 lg:grid-cols-12 lg:gap-x-12">
            <div class="lg:col-span-5 lg:pr-8" data-reveal>
                <SectionHeading
                    level="h2"
                    heading-id="home-why-title"
                    :eyebrow="contentText(content.eyebrow, 'MENGAPA SCH MANAGEMENT')"
                    :title="contentText(section.heading, 'Bukan Sekadar Membuat Website.')"
                    :description="contentText(content.description, 'Kami melihat website dan sistem digital sebagai bagian dari cara bisnis bekerja, berkomunikasi, dan berkembang.')"
                />
                <div class="mt-12 hidden items-center gap-4 lg:flex" aria-hidden="true">
                    <span class="h-px flex-1 bg-sch-border"/>
                    <span class="font-mono text-[0.625rem] uppercase tracking-[0.2em] text-slate-400">Principles / 4</span>
                </div>
            </div>

            <ol class="border-t border-sch-border lg:col-span-7" data-stagger="values">
                <li v-for="(item, index) in values" :key="`${item.title}-${index}`" class="why-row group relative grid gap-5 border-b border-sch-border py-7 sm:grid-cols-[3.5rem_minmax(0,0.8fr)_minmax(0,1.2fr)] sm:items-start sm:gap-6 sm:py-9">
                    <span class="font-mono text-xs font-bold tracking-[0.14em] text-sch-blue" aria-hidden="true">{{ index + 1 }}</span>
                    <h3 class="text-xl font-semibold tracking-[-0.03em] text-sch-navy sm:text-2xl">{{ contentText(item.title, `Nilai ${index + 1}`) }}</h3>
                    <p v-if="contentText(item.description, '')" class="max-w-xl text-sm leading-7 text-sch-muted">{{ contentText(item.description, '') }}</p>
                    <span class="absolute bottom-0 left-0 h-px w-0 bg-sch-blue transition-[width] duration-300 group-hover:w-full motion-reduce:transition-none" aria-hidden="true"/>
                </li>
            </ol>
        </div>
    </section>
</template>
