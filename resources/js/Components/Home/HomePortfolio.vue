<script setup>
import { computed } from 'vue';
import HomeProjectCard from './HomeProjectCard.vue';
import PublicIcon from '../Public/PublicIcon.vue';
import SmartLink from '../Public/SmartLink.vue';
import { contentText, contentUrl } from '../../Utils/homepageContent';

const props = defineProps({
    section: { type: Object, required: true },
    projects: { type: Array, default: () => [] },
});
const content = computed(() => props.section.content || {});
const primary = computed(() => props.projects[0]);
const supporting = computed(() => props.projects.slice(1, 3));
</script>

<template>
    <section v-if="projects.length" class="portfolio-section bg-[#08111C] py-12 text-[#F8FAFC] sm:py-16 lg:py-20" aria-labelledby="home-portfolio-title" data-home-section="portfolio">
        <div class="public-container-wide">
            <div class="grid min-w-0 gap-6 xl:grid-cols-[minmax(0,0.85fr)_minmax(0,1.55fr)_minmax(0,1.25fr)] xl:items-stretch" data-stagger="projects">
                <div class="flex min-w-0 flex-col items-start xl:pr-4">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#3EC6FF]">PORTFOLIO PILIHAN</p>
                    <h2 id="home-portfolio-title" class="mt-5 max-w-xl text-[clamp(2rem,3vw,2.75rem)] font-semibold leading-[1.12] tracking-[-0.035em] text-[#F8FAFC]">{{ contentText(section.heading, 'Project yang Kami Kembangkan') }}</h2>
                    <p class="mt-5 max-w-xl text-base leading-7 text-[#B8C3D1]">{{ contentText(content.description, 'Pilihan project yang menampilkan pendekatan SCH Management dalam menerjemahkan kebutuhan menjadi pengalaman digital yang terstruktur.') }}</p>
                    <SmartLink :href="contentUrl(content.cta_url, '/portfolio')" class="group mt-7 inline-flex min-h-11 items-center gap-2 border-b border-[#3EC6FF]/50 pb-1 text-sm font-semibold text-[#F8FAFC] transition-colors duration-200 hover:border-[#3EC6FF] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#3EC6FF]">
                        Lihat Semua Portfolio
                        <PublicIcon name="arrow-right" class="size-4 text-[#3EC6FF] transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transform-none"/>
                    </SmartLink>
                    <p class="mt-12 hidden border-l border-[#3EC6FF] pl-4 text-[0.6875rem] font-semibold uppercase leading-5 tracking-[0.2em] text-[#B8C3D1] xl:mt-auto xl:block">
                        Real Projects<br>Real Progress
                    </p>
                </div>

                <HomeProjectCard :project="primary" featured/>

                <div v-if="supporting.length" class="grid min-w-0 content-start gap-5 md:grid-cols-2 xl:grid-cols-1">
                    <HomeProjectCard v-for="project in supporting" :key="project.id" :project="project"/>
                </div>
            </div>
        </div>
    </section>
</template>
