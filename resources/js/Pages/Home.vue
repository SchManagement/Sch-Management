<script setup>
import { computed } from 'vue';
import PublicLayout from '../Layouts/PublicLayout.vue';
import HomeAbout from '../Components/Home/HomeAbout.vue';
import HomeFinalCTA from '../Components/Home/HomeFinalCTA.vue';
import HomeHero from '../Components/Home/HomeHero.vue';
import HomeInsights from '../Components/Home/HomeInsights.vue';
import HomePortfolio from '../Components/Home/HomePortfolio.vue';
import HomeProcess from '../Components/Home/HomeProcess.vue';
import HomeServices from '../Components/Home/HomeServices.vue';
import HomeTechnology from '../Components/Home/HomeTechnology.vue';
import HomeWhy from '../Components/Home/HomeWhy.vue';

const props = defineProps({
    sections: { type: Array, default: () => [] },
    services: { type: Array, default: () => [] },
    technologies: { type: Array, default: () => [] },
    projects: { type: Array, default: () => [] },
    articles: { type: Array, default: () => [] },
});

const sectionComponents = {
    hero: HomeHero,
    about: HomeAbout,
    services: HomeServices,
    why_sch: HomeWhy,
    portfolio: HomePortfolio,
    process: HomeProcess,
    technology: HomeTechnology,
    insights: HomeInsights,
    final_cta: HomeFinalCTA,
};

const hasHero = computed(() => props.sections.some(section => section.key === 'hero'));
const sectionProps = section => ({
    section,
    ...(section.key === 'services' ? { services: props.services } : {}),
    ...(section.key === 'technology' ? { technologies: props.technologies } : {}),
    ...(section.key === 'portfolio' ? { projects: props.projects } : {}),
    ...(section.key === 'insights' ? { articles: props.articles } : {}),
});
</script>

<template>
    <PublicLayout :header-mode="hasHero ? 'overlay' : 'solid'">
        <h1 v-if="!hasHero" class="sr-only">SCH Management</h1>
        <component
            :is="sectionComponents[section.key]"
            v-for="section in sections"
            :key="section.key"
            v-bind="sectionProps(section)"
        />
    </PublicLayout>
</template>
