<script setup>
import { computed } from 'vue';
import PublicButton from '../Public/PublicButton.vue';
import SectionHeading from '../Public/SectionHeading.vue';
import ServiceCard from '../Public/ServiceCard.vue';
import { contentText, contentUrl } from '../../Utils/homepageContent';

const props = defineProps({
    section: { type: Object, required: true },
    services: { type: Array, default: () => [] },
});
const content = computed(() => props.section.content || {});
</script>

<template>
    <section v-if="services.length" class="services-section relative overflow-hidden bg-sch-surface py-12 sm:py-16 lg:py-20" aria-labelledby="home-services-title" data-home-section="services">
        <div class="public-container relative">
            <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
                <SectionHeading
                    class="lg:col-span-8"
                    level="h2"
                    heading-id="home-services-title"
                    :eyebrow="contentText(content.eyebrow, 'LAYANAN')"
                    :title="contentText(section.heading, 'Solusi Digital yang Dirancang untuk Kebutuhan Bisnis.')"
                    :description="contentText(content.description, 'Mulai dari website perusahaan hingga sistem berbasis web, SCH Management membantu membangun solusi digital yang relevan dengan kebutuhan dan tujuan bisnis.')"
                />
                <div class="flex items-end justify-between gap-6 lg:col-span-4 lg:flex-col lg:items-end">
                    <span class="hidden font-mono text-[0.625rem] uppercase tracking-[0.2em] text-slate-400 sm:block" aria-hidden="true">Capabilities / {{ services.length }}</span>
                    <PublicButton :href="contentUrl(content.cta_url, '/services')" variant="outline" arrow class="self-start lg:self-auto">
                        {{ contentText(content.cta_label, 'Lihat Semua Layanan') }}
                    </PublicButton>
                </div>
            </div>

            <div class="sch-capabilities mt-14 grid border-t border-sch-border sm:grid-cols-2 xl:grid-cols-3" data-stagger="services">
                <ServiceCard v-for="(service, index) in services" :key="service.id" :service="service" :index="index" :tail="services.length % 3 === 0 && index === services.length - 1" editorial/>
            </div>
        </div>
    </section>
</template>
