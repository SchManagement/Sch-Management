<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import Breadcrumb from '../../Components/Public/Breadcrumb.vue';
import PlainTextContent from '../../Components/Public/PlainTextContent.vue';
import ProjectCard from '../../Components/Public/ProjectCard.vue';
import PublicButton from '../../Components/Public/PublicButton.vue';
import PublicCTA from '../../Components/Public/PublicCTA.vue';
import PublicIcon from '../../Components/Public/PublicIcon.vue';
import ResponsiveImage from '../../Components/Public/ResponsiveImage.vue';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
    project: { type: Object, required: true },
    relatedProjects: { type: Array, default: () => [] },
});

const metadata = computed(() => [
    props.project.client ? { label: 'Client', value: props.project.client } : null,
    props.project.industry ? { label: 'Industri', value: props.project.industry } : null,
    props.project.year ? { label: 'Tahun', value: props.project.year } : null,
    props.project.category ? { label: 'Kategori', value: props.project.category.name } : null,
].filter(Boolean));
</script>

<template>
    <PublicLayout header-mode="overlay">
        <article>
            <section class="sch-internal-hero sch-detail-hero relative isolate overflow-hidden bg-sch-navy py-14 text-white sm:py-20 lg:py-24" aria-labelledby="project-title" data-project-hero>
                <div class="pointer-events-none absolute -right-36 top-0 -z-10 size-[36rem] rounded-full bg-sch-blue/15 blur-3xl" aria-hidden="true"/>
                <div class="public-container">
                    <Breadcrumb dark :items="[
                        { label: 'Beranda', href: '/' },
                        { label: 'Portfolio', href: '/portfolio' },
                        { label: project.title, current: true },
                    ]"/>

                    <div class="mt-10 grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-center lg:gap-16">
                        <div>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-2 text-xs font-bold uppercase tracking-[0.16em] text-cyan-300">
                                <span v-if="project.category">{{ project.category.name }}</span>
                                <span v-if="project.category && project.industry" class="size-1 rounded-full bg-white/40" aria-hidden="true"/>
                                <span v-if="project.industry">{{ project.industry }}</span>
                                <span v-if="(project.category || project.industry) && project.year" class="size-1 rounded-full bg-white/40" aria-hidden="true"/>
                                <span v-if="project.year">{{ project.year }}</span>
                            </div>
                            <h1 id="project-title" class="public-heading mt-5 max-w-4xl">{{ project.title }}</h1>
                            <p v-if="project.short_description" class="public-body-lg mt-6 max-w-3xl text-slate-300">{{ project.short_description }}</p>
                            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                                <PublicButton href="/request-project" arrow>Diskusikan Project</PublicButton>
                                <PublicButton href="/portfolio" variant="inverted">Kembali ke Portfolio</PublicButton>
                            </div>
                        </div>

                        <div class="aspect-[4/3] overflow-hidden rounded-sch-lg border border-white/15 bg-sch-dark-blue shadow-sch-md">
                            <ResponsiveImage
                                :src="project.thumbnail"
                                :alt="project.thumbnail_alt || ''"
                                width="1120"
                                height="840"
                                loading="eager"
                                sizes="(min-width: 1024px) 52vw, 100vw"
                            />
                        </div>
                    </div>
                </div>
            </section>

            <section
                v-if="project.overview || metadata.length || project.service || project.project_url"
                class="py-16 sm:py-20 lg:py-24"
                :aria-labelledby="project.overview ? 'project-overview-title' : undefined"
                :aria-label="project.overview ? undefined : 'Informasi project'"
            >
                <div class="public-container grid gap-12 lg:grid-cols-[1fr_0.58fr] lg:gap-20">
                    <div v-if="project.overview">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">PROJECT OVERVIEW</p>
                        <h2 id="project-overview-title" class="public-subheading mt-4">Konteks Project</h2>
                        <PlainTextContent :text="project.overview" lead class="mt-6 max-w-3xl"/>
                    </div>
                    <aside v-if="metadata.length || project.service || project.project_url" :aria-label="`Informasi project ${project.title}`" class="self-start border-y border-sch-border" data-reveal>
                        <dl>
                            <div v-for="item in metadata" :key="item.label" class="grid grid-cols-[6rem_1fr] gap-5 border-b border-sch-border py-4 last:border-b-0 sm:grid-cols-[8rem_1fr]">
                                <dt class="text-sm font-semibold text-sch-muted">{{ item.label }}</dt>
                                <dd class="min-w-0 text-sm font-bold text-sch-navy">{{ item.value }}</dd>
                            </div>
                            <div v-if="project.service" class="grid grid-cols-[6rem_1fr] gap-5 border-b border-sch-border py-4 last:border-b-0 sm:grid-cols-[8rem_1fr]">
                                <dt class="text-sm font-semibold text-sch-muted">Layanan</dt>
                                <dd class="min-w-0 text-sm font-bold">
                                    <Link :href="`/services/${project.service.slug}`" class="text-sch-blue underline-offset-4 hover:underline">{{ project.service.name }}</Link>
                                </dd>
                            </div>
                            <div v-if="project.project_url" class="grid grid-cols-[6rem_1fr] gap-5 border-b border-sch-border py-4 last:border-b-0 sm:grid-cols-[8rem_1fr]">
                                <dt class="text-sm font-semibold text-sch-muted">Website</dt>
                                <dd class="min-w-0">
                                    <a :href="project.project_url" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-11 items-center gap-2 text-sm font-bold text-sch-blue underline-offset-4 hover:underline">
                                        Kunjungi project
                                        <PublicIcon name="arrow-up-right" class="size-4"/>
                                        <span class="sr-only">(dibuka di tab baru)</span>
                                    </a>
                                </dd>
                            </div>
                        </dl>
                    </aside>
                </div>
            </section>

            <section v-if="project.challenge || project.solution" class="bg-sch-surface py-16 sm:py-20 lg:py-24" aria-label="Tantangan dan solusi project">
                <div class="public-container grid gap-12 lg:gap-16" :class="project.challenge && project.solution ? 'lg:grid-cols-2' : 'max-w-4xl'">
                    <div v-if="project.challenge">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">THE CHALLENGE</p>
                        <h2 id="project-challenge-title" class="public-subheading mt-4">Tantangan Project</h2>
                        <PlainTextContent :text="project.challenge" class="mt-6"/>
                    </div>
                    <div v-if="project.solution">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">THE SOLUTION</p>
                        <h2 id="project-solution-title" class="public-subheading mt-4">Solusi yang Dikembangkan</h2>
                        <PlainTextContent :text="project.solution" class="mt-6"/>
                    </div>
                </div>
            </section>

            <section v-if="project.technologies.length" class="py-16 sm:py-20 lg:py-24" aria-labelledby="project-technology-title">
                <div class="public-container grid gap-10 lg:grid-cols-[0.55fr_1fr] lg:gap-20">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">TECHNOLOGY</p>
                        <h2 id="project-technology-title" class="public-subheading mt-4">Teknologi yang Digunakan.</h2>
                    </div>
                    <ul class="flex flex-wrap content-start gap-3" aria-label="Teknologi project">
                        <li v-for="technology in project.technologies" :key="technology.slug" class="inline-flex min-h-11 items-center rounded-sch-sm border border-sch-border bg-white px-4 text-sm font-bold text-sch-navy shadow-sch-sm">
                            {{ technology.name }}
                        </li>
                    </ul>
                </div>
            </section>

            <section v-if="project.images.length" class="bg-sch-midnight py-16 text-white sm:py-20 lg:py-24" aria-labelledby="project-gallery-title" data-project-gallery>
                <div class="public-container">
                    <div class="max-w-3xl">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-cyan-300">PROJECT GALLERY</p>
                        <h2 id="project-gallery-title" class="public-subheading mt-4">Tampilan dan Dokumentasi Project.</h2>
                    </div>
                    <div class="mt-10 grid gap-5 lg:grid-cols-2">
                        <figure
                            v-for="(image, index) in project.images"
                            :key="image.id"
                            class="overflow-hidden rounded-sch-lg border border-white/15 bg-sch-dark-blue"
                            :class="index === 0 && project.images.length > 2 ? 'lg:col-span-2' : ''"
                        >
                            <div :class="index === 0 && project.images.length > 2 ? 'aspect-[16/9]' : 'aspect-[4/3]'">
                                <ResponsiveImage
                                    :src="image.url"
                                    :alt="image.alt_text"
                                    width="1280"
                                    height="960"
                                    loading="lazy"
                                    sizes="(min-width: 1024px) 50vw, 100vw"
                                    class="transition-transform duration-300 hover:scale-[1.015] motion-reduce:transform-none"
                                />
                            </div>
                            <figcaption v-if="image.caption" class="border-t border-white/10 px-5 py-4 text-sm leading-6 text-slate-300">{{ image.caption }}</figcaption>
                        </figure>
                    </div>
                </div>
            </section>

            <section v-if="project.results" class="py-16 sm:py-20 lg:py-24" aria-labelledby="project-results-title">
                <div class="public-container grid gap-8 lg:grid-cols-[0.48fr_1fr] lg:gap-20">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">RESULTS</p>
                        <h2 id="project-results-title" class="public-subheading mt-4">Hasil yang Tercatat.</h2>
                    </div>
                    <PlainTextContent :text="project.results" lead class="max-w-3xl"/>
                </div>
            </section>

            <section v-if="relatedProjects.length" class="bg-sch-surface py-16 sm:py-20 lg:py-24" aria-labelledby="related-projects-title">
                <div class="public-container">
                    <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                        <div class="max-w-3xl">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">RELATED PROJECTS</p>
                            <h2 id="related-projects-title" class="public-subheading mt-4">Project Lain dengan Konteks Terkait.</h2>
                        </div>
                        <PublicButton href="/portfolio" variant="outline">Lihat Semua Project</PublicButton>
                    </div>
                    <div class="mt-10 grid gap-5" :class="relatedProjects.length > 1 ? 'lg:grid-cols-2' : 'max-w-3xl'">
                        <ProjectCard v-for="item in relatedProjects" :key="item.id" :project="item" data-project-card/>
                    </div>
                </div>
            </section>

            <section class="bg-white py-20 sm:py-24 lg:py-28">
                <div class="public-container">
                    <PublicCTA
                        heading="Punya kebutuhan digital dengan konteks serupa?"
                        description="Diskusikan tujuan, kebutuhan pengguna, dan ruang lingkup project Anda untuk menentukan pendekatan pengembangan yang tepat."
                        :primary-action="{ label: 'Diskusikan Project', href: '/request-project' }"
                        :secondary-action="{ label: 'Lihat Layanan', href: '/services' }"
                    />
                </div>
            </section>
        </article>
    </PublicLayout>
</template>
