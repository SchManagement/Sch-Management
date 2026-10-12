<script setup>
import { computed } from 'vue';
import Breadcrumb from '../../Components/Public/Breadcrumb.vue';
import PlainTextContent from '../../Components/Public/PlainTextContent.vue';
import ProjectCard from '../../Components/Public/ProjectCard.vue';
import PublicButton from '../../Components/Public/PublicButton.vue';
import PublicCTA from '../../Components/Public/PublicCTA.vue';
import PublicIcon from '../../Components/Public/PublicIcon.vue';
import ResponsiveImage from '../../Components/Public/ResponsiveImage.vue';
import StructuredContentList from '../../Components/Public/StructuredContentList.vue';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
    service: { type: Object, required: true },
    relatedProjects: { type: Array, default: () => [] },
});

const supportedIcons = new Set([
    'code', 'globe', 'layers', 'layout', 'maintenance', 'server', 'support', 'technology',
]);
const iconName = computed(() => supportedIcons.has(props.service.icon) ? props.service.icon : 'layers');
const hasProblemSolution = computed(() => props.service.business_problem || props.service.solution);
</script>

<template>
    <PublicLayout header-mode="overlay">
        <section class="sch-internal-hero sch-detail-hero relative isolate overflow-hidden bg-sch-navy py-14 text-white sm:py-20 lg:py-24" aria-labelledby="service-title">
            <div class="pointer-events-none absolute -right-32 top-0 -z-10 size-[34rem] rounded-full bg-sch-blue/15 blur-3xl" aria-hidden="true"/>
            <div class="public-container">
                <Breadcrumb dark :items="[
                    { label: 'Beranda', href: '/' },
                    { label: 'Layanan', href: '/services' },
                    { label: service.name, current: true },
                ]"/>

                <div class="mt-10 grid gap-12 lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:gap-20">
                    <div>
                        <span class="grid size-14 place-items-center rounded-sch-md border border-white/15 bg-white/5 text-cyan-300" aria-hidden="true">
                            <PublicIcon :name="iconName" class="size-7"/>
                        </span>
                        <p class="mt-7 text-xs font-bold uppercase tracking-[0.18em] text-cyan-300">LAYANAN SCH MANAGEMENT</p>
                        <h1 id="service-title" class="public-heading mt-4 max-w-4xl">{{ service.name }}</h1>
                        <p v-if="service.short_description" class="public-body-lg mt-6 max-w-3xl text-slate-300">{{ service.short_description }}</p>
                        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                            <PublicButton href="/request-project" arrow>Diskusikan Project</PublicButton>
                            <PublicButton href="/services" variant="inverted">Kembali ke Layanan</PublicButton>
                        </div>
                    </div>

                    <div v-if="service.image" class="aspect-[4/3] overflow-hidden rounded-sch-lg border border-white/15 bg-sch-dark-blue">
                        <ResponsiveImage :src="service.image" :alt="`Ilustrasi layanan ${service.name}`" width="900" height="675" loading="eager"/>
                    </div>
                    <div v-else class="sch-story-art grid aspect-[4/3] place-items-center bg-sch-dark-blue" aria-hidden="true">
                        <img :src="'/images/brand/sch-icon.png'" alt="" width="1254" height="1254"/>
                    </div>
                </div>
            </div>
        </section>

        <section v-if="service.full_description" class="py-16 sm:py-20 lg:py-24" aria-labelledby="service-overview-title">
            <div class="public-container grid gap-8 lg:grid-cols-[0.48fr_1fr] lg:gap-20">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">OVERVIEW</p>
                    <h2 id="service-overview-title" class="public-subheading mt-4">Tentang Layanan Ini</h2>
                </div>
                <PlainTextContent :text="service.full_description" lead class="max-w-3xl"/>
            </div>
        </section>

        <section v-if="hasProblemSolution" class="bg-sch-surface py-16 sm:py-20 lg:py-24" aria-label="Masalah bisnis dan solusi">
            <div class="public-container grid gap-4 lg:gap-0" :class="service.business_problem && service.solution ? 'lg:grid-cols-2' : 'max-w-4xl'">
                <article v-if="service.business_problem" class="sch-dark-depth bg-sch-midnight px-6 py-9 text-white sm:px-10 sm:py-12" aria-labelledby="service-problem-title">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-cyan-300">BUSINESS PROBLEM</p>
                    <h2 id="service-problem-title" class="public-subheading mt-4">Tantangan yang Perlu Dijawab</h2>
                    <PlainTextContent :text="service.business_problem" dark class="mt-6"/>
                </article>
                <article v-if="service.solution" class="border border-sch-border bg-white px-6 py-9 sm:px-10 sm:py-12" aria-labelledby="service-solution-title">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">OUR SOLUTION</p>
                    <h2 id="service-solution-title" class="public-subheading mt-4">Pendekatan Solusi</h2>
                    <PlainTextContent :text="service.solution" class="mt-6"/>
                </article>
            </div>
        </section>

        <section v-if="service.features.length" class="py-16 sm:py-20 lg:py-24" aria-labelledby="service-features-title">
            <div class="public-container grid gap-10 lg:grid-cols-[0.55fr_1fr] lg:gap-20">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">FITUR & MANFAAT</p>
                    <h2 id="service-features-title" class="public-subheading mt-4">Elemen yang Mendukung Kebutuhan Layanan.</h2>
                </div>
                <StructuredContentList :items="service.features" columns aria-label="Fitur dan manfaat layanan"/>
            </div>
        </section>

        <section v-if="service.deliverables.length" class="bg-sch-surface py-16 sm:py-20 lg:py-24" aria-labelledby="service-deliverables-title">
            <div class="public-container grid gap-10 lg:grid-cols-[0.55fr_1fr] lg:gap-20">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">DELIVERABLES</p>
                    <h2 id="service-deliverables-title" class="public-subheading mt-4">Yang Dipersiapkan dalam Project.</h2>
                </div>
                <StructuredContentList :items="service.deliverables" columns aria-label="Deliverables layanan"/>
            </div>
        </section>

        <section v-if="service.development_process.length" class="py-16 sm:py-20 lg:py-24" aria-labelledby="service-process-title">
            <div class="public-container">
                <div class="max-w-3xl">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">DEVELOPMENT PROCESS</p>
                    <h2 id="service-process-title" class="public-subheading mt-4">Proses Pengembangan Layanan.</h2>
                    <p class="mt-4 text-base leading-7 text-sch-muted">Tahapan berikut mengikuti konfigurasi spesifik layanan ini.</p>
                </div>
                <StructuredContentList :items="service.development_process" ordered columns aria-label="Proses pengembangan layanan" class="mt-10"/>
            </div>
        </section>

        <section v-if="service.suitable_for.length" class="bg-sch-surface py-16 sm:py-20 lg:py-24" aria-labelledby="service-suitable-title">
            <div class="public-container grid gap-10 lg:grid-cols-[0.55fr_1fr] lg:gap-20">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">SUITABLE FOR</p>
                    <h2 id="service-suitable-title" class="public-subheading mt-4">Kebutuhan yang Sesuai untuk Layanan Ini.</h2>
                </div>
                <StructuredContentList :items="service.suitable_for" columns aria-label="Kebutuhan yang sesuai"/>
            </div>
        </section>

        <section v-if="relatedProjects.length" class="bg-sch-midnight py-16 text-white sm:py-20 lg:py-24" aria-labelledby="service-projects-title">
            <div class="public-container">
                <div class="max-w-3xl">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-cyan-300">RELEVANT PROJECTS</p>
                    <h2 id="service-projects-title" class="public-subheading mt-4">Project yang Berkaitan dengan Layanan Ini.</h2>
                </div>
                <div class="mt-10 grid gap-5" :class="relatedProjects.length > 1 ? 'lg:grid-cols-2' : 'max-w-3xl'">
                    <ProjectCard v-for="project in relatedProjects" :key="project.id" :project="project"/>
                </div>
            </div>
        </section>

        <section class="bg-white py-20 sm:py-24 lg:py-28">
            <div class="public-container">
                <PublicCTA
                    :heading="`Siap Mendiskusikan ${service.name}?`"
                    description="Ceritakan kebutuhan, ruang lingkup, dan tujuan bisnis Anda untuk menentukan langkah pengembangan yang tepat."
                    :primary-action="{ label: 'Diskusikan Project', href: '/request-project' }"
                    :secondary-action="{ label: 'Lihat Semua Layanan', href: '/services' }"
                />
            </div>
        </section>
    </PublicLayout>
</template>
