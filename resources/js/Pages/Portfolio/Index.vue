<script setup>
import { Link } from '@inertiajs/vue3';
import Breadcrumb from '../../Components/Public/Breadcrumb.vue';
import ProjectCard from '../../Components/Public/ProjectCard.vue';
import PublicButton from '../../Components/Public/PublicButton.vue';
import PublicCTA from '../../Components/Public/PublicCTA.vue';
import PublicPagination from '../../Components/Public/PublicPagination.vue';
import PublicLayout from '../../Layouts/PublicLayout.vue';

defineProps({
    projects: { type: Object, required: true },
    categories: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({ category: null }) },
});

const categoryUrl = (slug) => slug ? `/portfolio?category=${encodeURIComponent(slug)}` : '/portfolio';
</script>

<template>
    <PublicLayout header-mode="overlay" style="--sch-section-space: clamp(3rem, 6vw, 5rem)">
        <section class="sch-internal-hero relative isolate overflow-hidden bg-sch-navy py-16 text-white sm:py-20 lg:py-24" aria-labelledby="portfolio-title">
            <div class="pointer-events-none absolute -right-24 top-0 -z-10 size-96 rounded-full bg-sch-blue/15 blur-3xl" aria-hidden="true"/>
            <div class="public-container">
                <Breadcrumb dark :items="[{ label: 'Beranda', href: '/' }, { label: 'Portfolio', current: true }]"/>
                <div class="sch-internal-hero-main mt-10">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-300">PORTFOLIO</p>
                        <h1 id="portfolio-title" class="public-heading mt-4 max-w-4xl">Project Digital yang Dibangun dengan Tujuan yang Jelas.</h1>
                        <p class="public-body-lg mt-6 max-w-2xl text-slate-300">
                            Pilihan pekerjaan web development dan solusi digital yang ditampilkan berdasarkan implementasi nyata, konteks kebutuhan, serta data project yang tersedia.
                        </p>
                    </div>
                    <div class="sch-hero-art" aria-hidden="true"><img :src="'/images/brand/sch-icon.png'" alt="" width="1254" height="1254"/></div>
                </div>
            </div>
        </section>

        <section class="public-section bg-sch-surface" aria-labelledby="portfolio-grid-title">
            <div class="public-container">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">SELECTED WORK</p>
                        <h2 id="portfolio-grid-title" class="public-subheading mt-4">Implementasi Digital dengan Konteks yang Transparan.</h2>
                    </div>

                    <nav v-if="categories.length" aria-label="Filter kategori portfolio" class="lg:max-w-xl" data-reveal>
                        <p class="mb-3 text-xs font-bold uppercase tracking-[0.14em] text-sch-muted">Filter kategori</p>
                        <div class="flex flex-wrap gap-2">
                            <Link
                                :href="categoryUrl(null)"
                                preserve-scroll
                                class="inline-flex min-h-11 items-center rounded-sch-sm border px-4 text-sm font-bold transition-colors"
                                :class="!filters.category ? 'border-sch-navy bg-sch-navy text-white' : 'border-sch-border bg-white text-sch-muted hover:border-blue-200 hover:text-sch-navy'"
                                :aria-current="!filters.category ? 'page' : undefined"
                            >
                                Semua Project
                            </Link>
                            <Link
                                v-for="category in categories"
                                :key="category.slug"
                                :href="categoryUrl(category.slug)"
                                preserve-scroll
                                class="inline-flex min-h-11 items-center rounded-sch-sm border px-4 text-sm font-bold transition-colors"
                                :class="filters.category === category.slug ? 'border-sch-navy bg-sch-navy text-white' : 'border-sch-border bg-white text-sch-muted hover:border-blue-200 hover:text-sch-navy'"
                                :aria-current="filters.category === category.slug ? 'page' : undefined"
                            >
                                {{ category.name }}
                            </Link>
                        </div>
                    </nav>
                </div>

                <div v-if="projects.data.length" class="mt-12 grid gap-5 lg:grid-cols-2" data-stagger="portfolio-index">
                    <ProjectCard
                        v-for="(project, index) in projects.data"
                        :key="project.id"
                        :project="project"
                        :featured="index === 0 && project.featured"
                        :class="index === 0 && project.featured ? 'lg:col-span-2' : ''"
                        data-project-card
                    />
                </div>

                <div v-else class="mt-12 border-y border-sch-border py-10 sm:flex sm:items-center sm:justify-between sm:gap-8">
                    <div class="max-w-2xl">
                        <h3 class="text-xl font-bold text-sch-navy">Mari mulai dari kebutuhan digital Anda.</h3>
                        <p class="mt-3 text-base leading-7 text-sch-muted">
                            Belum ada project yang dapat ditampilkan untuk pilihan ini. Diskusikan konteks dan tujuan bisnis Anda untuk menentukan pendekatan yang relevan.
                        </p>
                    </div>
                    <PublicButton href="/request-project" arrow class="mt-6 sm:mt-0">Diskusikan Project</PublicButton>
                </div>

                <PublicPagination
                    :links="projects.links"
                    :from="projects.from"
                    :to="projects.to"
                    :total="projects.total"
                    aria-label="Paginasi portfolio"
                    item-label="project"
                />
            </div>
        </section>

        <section class="sch-about-cta-section">
            <div class="public-container">
                <PublicCTA
                    class="sch-about-cta"
                    heading="Punya project digital yang ingin dikembangkan?"
                    description="Ceritakan kebutuhan dan tujuan project Anda untuk menyusun ruang lingkup serta arah pengembangan yang tepat."
                    :primary-action="{ label: 'Diskusikan Project', href: '/request-project' }"
                    :secondary-action="{ label: 'Lihat Layanan', href: '/services' }"
                />
            </div>
        </section>
    </PublicLayout>
</template>
