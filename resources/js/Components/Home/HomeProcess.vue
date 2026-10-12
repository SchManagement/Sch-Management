<script setup>
import { computed } from 'vue';
import SectionHeading from '../Public/SectionHeading.vue';
import { contentItems, contentText } from '../../Utils/homepageContent';

const props = defineProps({ section: { type: Object, required: true } });
const content = computed(() => props.section.content || {});
const steps = computed(() => contentItems(content.value.steps, [
    { title: 'Discovery', description: 'Memahami kebutuhan, konteks bisnis, pengguna, dan hasil yang ingin dicapai.' },
    { title: 'Planning', description: 'Menyusun ruang lingkup, struktur, prioritas, dan arah teknis project.' },
    { title: 'UI/UX Design', description: 'Merancang alur serta antarmuka yang jelas, konsisten, dan mudah digunakan.' },
    { title: 'Development', description: 'Membangun solusi dengan fondasi kode yang terstruktur dan dapat dipelihara.' },
    { title: 'Testing', description: 'Memeriksa fungsi, responsivitas, kualitas, dan kesiapan sebelum peluncuran.' },
    { title: 'Launch & Support', description: 'Menyiapkan peluncuran dan dukungan lanjutan sesuai kebutuhan project.' },
], 6).map((step, index) => step && typeof step === 'object' ? step : {
    title: typeof step === 'string' ? step : `Tahap ${index + 1}`,
    description: '',
}));
</script>

<template>
    <section class="relative overflow-hidden bg-white pb-4 pt-12 sm:pb-6 sm:pt-16 lg:pb-8 lg:pt-20" aria-labelledby="home-process-title" data-home-section="process">
        <div class="public-container">
            <div class="grid gap-9 lg:grid-cols-12 lg:items-end">
                <SectionHeading
                    class="lg:col-span-8"
                    level="h2"
                    heading-id="home-process-title"
                    :eyebrow="contentText(content.eyebrow, 'PROSES PENGEMBANGAN')"
                    :title="contentText(section.heading, 'Bagaimana Kami Bekerja.')"
                    :description="contentText(content.description, 'Proses yang terarah membantu setiap keputusan tetap terhubung dengan kebutuhan bisnis dan pengalaman pengguna.')"
                />
                <div class="hidden lg:col-span-4 lg:block" aria-hidden="true"><span class="block h-px bg-sch-border"/></div>
            </div>

            <div class="relative mt-10 lg:mt-16">
                <span class="process-progress pointer-events-none absolute z-10 bg-sch-blue" data-process-progress aria-hidden="true"/>
                <ol class="process-rail relative grid lg:grid-cols-6" data-stagger="process">
                    <li v-for="(step, index) in steps" :key="`${step.title}-${index}`" class="process-step group relative border-b border-sch-border py-6 pl-12 pr-2 last:border-b-0 last:pb-0 lg:border-b-0 lg:pb-0 lg:pl-0 lg:pr-6 lg:pt-10" data-process-step>
                    <span class="process-node absolute left-0 top-7 z-10 grid size-8 place-items-center border border-sch-blue bg-white font-mono text-xs font-bold text-sch-blue lg:left-0 lg:top-0 lg:-translate-y-1/2" aria-hidden="true">
                        {{ index + 1 }}
                    </span>
                    <h3 class="text-lg font-bold tracking-[-0.03em] text-sch-navy xl:text-xl">{{ contentText(step.title, `Tahap ${index + 1}`) }}</h3>
                    <p v-if="contentText(step.description, '')" class="mt-3 max-w-sm text-sm leading-6 text-sch-muted">{{ contentText(step.description, '') }}</p>
                    </li>
                </ol>
            </div>
        </div>
    </section>
</template>

<style scoped>
.process-rail::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0.9375rem;
    width: 1px;
    content: '';
    background: var(--color-sch-border);
}

.process-progress { top: 0; bottom: 0; left: 0.9375rem; width: 1px; transform-origin: top; }

@media (min-width: 1024px) {
    .process-rail { border-top: 1px solid var(--color-sch-border); }
    .process-rail::before { display: none; }
    .process-progress { top: 0; right: 0; bottom: auto; left: 0; width: auto; height: 1px; transform-origin: left; }
}
</style>
