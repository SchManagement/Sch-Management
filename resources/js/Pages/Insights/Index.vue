<script setup>
import { computed, ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import ArticleCard from '../../Components/Public/ArticleCard.vue';
import Breadcrumb from '../../Components/Public/Breadcrumb.vue';
import PublicButton from '../../Components/Public/PublicButton.vue';
import PublicCTA from '../../Components/Public/PublicCTA.vue';
import PublicIcon from '../../Components/Public/PublicIcon.vue';
import PublicPagination from '../../Components/Public/PublicPagination.vue';
import ResponsiveImage from '../../Components/Public/ResponsiveImage.vue';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
    articles: { type: Object, required: true },
    featuredArticle: { type: Object, default: null },
    categories: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({ category: null, search: '' }) },
    hasPublishedArticles: Boolean,
});

const searchInput = ref(props.filters.search || '');
const hasActiveFilters = computed(() => Boolean(props.filters.category || props.filters.search));
const featuredDate = computed(() => props.featuredArticle?.published_at
    ? new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }).format(new Date(props.featuredArticle.published_at))
    : null);

watch(() => props.filters.search, value => {
    searchInput.value = value || '';
});

const categoryUrl = (slug) => {
    const parameters = [];

    if (slug) parameters.push(`category=${encodeURIComponent(slug)}`);
    if (props.filters.search) parameters.push(`search=${encodeURIComponent(props.filters.search)}`);

    return parameters.length ? `/insights?${parameters.join('&')}` : '/insights';
};

const submitSearch = () => {
    const search = searchInput.value.trim();
    const parameters = {};

    if (props.filters.category) parameters.category = props.filters.category;
    if (search) parameters.search = search;

    router.get('/insights', parameters, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};
</script>

<template>
    <PublicLayout header-mode="overlay" style="--sch-section-space: clamp(3rem, 6vw, 5rem)">
        <section class="sch-internal-hero relative isolate overflow-hidden bg-sch-navy py-16 text-white sm:py-20 lg:py-24" aria-labelledby="insights-title">
            <div class="pointer-events-none absolute -right-24 top-0 -z-10 size-96 rounded-full bg-sch-blue/15 blur-3xl" aria-hidden="true"/>
            <div class="public-container">
                <Breadcrumb dark :items="[{ label: 'Beranda', href: '/' }, { label: 'Insight', current: true }]"/>
                <div class="sch-internal-hero-main mt-10">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-300">INSIGHT</p>
                        <h1 id="insights-title" class="public-heading mt-4 max-w-4xl">Insight, Teknologi, dan Perspektif Digital.</h1>
                        <p class="public-body-lg mt-6 max-w-2xl text-slate-300">
                            Perspektif praktis mengenai web development, teknologi, UI/UX, website maintenance, solusi IT, dan digitalisasi bisnis.
                        </p>
                    </div>
                    <div class="sch-hero-art" aria-hidden="true"><img :src="'/images/brand/sch-icon.png'" alt="" width="1254" height="1254"/></div>
                </div>
            </div>
        </section>

        <section v-if="featuredArticle" class="bg-white py-12 sm:py-16 lg:py-20" aria-labelledby="featured-article-title">
            <div class="public-container">
                <article class="group isolate overflow-hidden rounded-sch-xl bg-sch-navy text-white shadow-sch-md lg:grid lg:grid-cols-[0.9fr_1.1fr]" data-featured-article>
                    <div class="flex flex-col justify-center px-6 py-10 sm:px-10 sm:py-12 lg:px-14 lg:py-16">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-300">FEATURED INSIGHT</p>
                        <div class="mt-5 flex flex-wrap items-center gap-x-3 gap-y-2 text-xs font-semibold text-slate-300">
                            <span v-if="featuredArticle.category">{{ featuredArticle.category.name }}</span>
                            <span v-if="featuredArticle.category && featuredDate" class="size-1 rounded-full bg-white/35" aria-hidden="true"/>
                            <time v-if="featuredDate" :datetime="featuredArticle.published_at">{{ featuredDate }}</time>
                        </div>
                        <h2 id="featured-article-title" class="public-subheading mt-4 max-w-2xl text-white">{{ featuredArticle.title }}</h2>
                        <p v-if="featuredArticle.excerpt" class="mt-5 max-w-2xl text-base leading-8 text-slate-300">{{ featuredArticle.excerpt }}</p>
                        <p v-if="featuredArticle.author" class="mt-5 text-sm font-semibold text-slate-300">Oleh {{ featuredArticle.author.name }}</p>
                        <PublicButton :href="`/insights/${featuredArticle.slug}`" variant="light" arrow class="mt-8 self-start">Baca Insight</PublicButton>
                    </div>

                    <div v-if="featuredArticle.cover_image" class="min-h-72 overflow-hidden bg-sch-dark-blue lg:min-h-full">
                        <ResponsiveImage
                            :src="featuredArticle.cover_image"
                            :alt="`Gambar sampul artikel ${featuredArticle.title}`"
                            width="1120"
                            height="840"
                            loading="eager"
                            sizes="(min-width: 1024px) 55vw, 100vw"
                            class="transition-transform duration-300 group-hover:scale-[1.02] motion-reduce:transform-none"
                        />
                    </div>
                    <div v-else class="hidden min-h-96 place-items-center bg-sch-dark-blue lg:grid" aria-hidden="true">
                        <img :src="'/images/brand/sch-icon.png'" alt="" width="1254" height="1254" class="w-48 object-contain"/>
                    </div>
                </article>
            </div>
        </section>

        <section class="public-section bg-sch-surface" aria-labelledby="articles-title">
            <div class="public-container">
                <div class="max-w-3xl">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">LATEST INSIGHTS</p>
                    <h2 id="articles-title" class="public-subheading mt-4">Artikel untuk Membantu Memahami Pilihan Digital.</h2>
                </div>

                <div v-if="hasPublishedArticles" class="sch-insight-toolbar mt-10 border-y border-sch-border py-6 sm:py-7" data-reveal>
                    <form method="get" action="/insights" role="search" class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end" @submit.prevent="submitSearch">
                        <input v-if="filters.category" type="hidden" name="category" :value="filters.category">
                        <div>
                            <label for="insight-search" class="text-sm font-bold text-sch-navy">Cari insight</label>
                            <div class="relative mt-2">
                                <PublicIcon name="search" class="pointer-events-none absolute left-4 top-1/2 size-5 -translate-y-1/2 text-sch-muted"/>
                                <input
                                    id="insight-search"
                                    v-model="searchInput"
                                    name="search"
                                    type="search"
                                    maxlength="100"
                                    autocomplete="off"
                                    placeholder="Cari berdasarkan judul atau ringkasan..."
                                    class="min-h-12 w-full rounded-none border-0 border-b border-sch-border bg-transparent py-3 pl-12 pr-4 text-base text-sch-ink shadow-none placeholder:text-slate-400 focus:border-sch-blue focus:ring-0"
                                >
                            </div>
                        </div>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <PublicButton type="submit" class="sm:min-w-28">Cari</PublicButton>
                            <PublicButton v-if="hasActiveFilters" href="/insights" variant="outline">Reset Filter</PublicButton>
                        </div>
                    </form>

                    <nav v-if="categories.length" aria-label="Filter kategori insight" class="mt-7 border-t border-sch-border/70 pt-5">
                        <p class="mb-3 text-xs font-bold uppercase tracking-[0.14em] text-sch-muted">Kategori artikel</p>
                        <div class="flex flex-wrap gap-2">
                            <Link
                                :href="categoryUrl(null)"
                                preserve-scroll
                                class="inline-flex min-h-11 items-center border-b-2 px-4 text-sm font-bold transition-colors"
                                :class="!filters.category ? 'border-sch-blue text-sch-blue' : 'border-transparent text-sch-muted hover:border-blue-200 hover:text-sch-navy'"
                                :aria-current="!filters.category ? 'page' : undefined"
                            >Semua</Link>
                            <Link
                                v-for="category in categories"
                                :key="category.slug"
                                :href="categoryUrl(category.slug)"
                                preserve-scroll
                                class="inline-flex min-h-11 items-center border-b-2 px-4 text-sm font-bold transition-colors"
                                :class="filters.category === category.slug ? 'border-sch-blue text-sch-blue' : 'border-transparent text-sch-muted hover:border-blue-200 hover:text-sch-navy'"
                                :aria-current="filters.category === category.slug ? 'page' : undefined"
                            >{{ category.name }}</Link>
                        </div>
                    </nav>
                </div>

                <p v-if="hasPublishedArticles" class="mt-8 text-sm text-sch-muted" aria-live="polite">
                    <template v-if="articles.total">
                        Menampilkan {{ articles.total }} {{ featuredArticle ? 'artikel lainnya' : 'artikel' }}<template v-if="filters.search"> untuk “{{ filters.search }}”</template>.
                    </template>
                    <template v-else-if="featuredArticle">Artikel unggulan tersedia di atas.</template>
                    <template v-else>Tidak ada artikel yang sesuai dengan filter saat ini.</template>
                </p>

                <div v-if="articles.data.length" class="mt-8 grid gap-x-6 gap-y-12 sm:grid-cols-2 xl:grid-cols-3" data-stagger="insights-index">
                    <ArticleCard v-for="(article, index) in articles.data" :key="article.id" :article="article" :prominent="index === 0" :class="index === 0 ? 'sm:col-span-2 xl:col-span-2' : ''"/>
                </div>

                <div v-else-if="hasActiveFilters" class="mt-10 border-y border-sch-border py-10 sm:flex sm:items-center sm:justify-between sm:gap-8">
                    <div class="max-w-2xl">
                        <h3 class="text-xl font-bold text-sch-navy">Insight yang dicari belum ditemukan.</h3>
                        <p class="mt-3 text-base leading-7 text-sch-muted">Coba gunakan kata yang lebih umum atau hapus filter untuk melihat seluruh artikel yang tersedia.</p>
                    </div>
                    <PublicButton href="/insights" variant="outline" class="mt-6 sm:mt-0">Lihat Semua Insight</PublicButton>
                </div>

                <div v-else-if="!hasPublishedArticles" class="mt-10 border-y border-sch-border py-10 sm:flex sm:items-center sm:justify-between sm:gap-8">
                    <div class="max-w-2xl">
                        <h3 class="text-xl font-bold text-sch-navy">Perspektif digital sedang dipersiapkan.</h3>
                        <p class="mt-3 text-base leading-7 text-sch-muted">Sementara itu, Anda dapat melihat layanan SCH Management atau mendiskusikan kebutuhan digital bisnis.</p>
                    </div>
                    <PublicButton href="/services" arrow class="mt-6 sm:mt-0">Lihat Layanan</PublicButton>
                </div>

                <PublicPagination
                    :links="articles.links"
                    :from="articles.from"
                    :to="articles.to"
                    :total="articles.total"
                    aria-label="Paginasi insight"
                    item-label="artikel"
                />
            </div>
        </section>

        <section class="sch-about-cta-section">
            <div class="public-container">
                <PublicCTA
                    class="sch-about-cta"
                    heading="Punya kebutuhan digital yang ingin dibahas?"
                    description="Mulai dari konteks bisnis Anda untuk menentukan solusi website, sistem digital, atau dukungan teknologi yang relevan."
                    :primary-action="{ label: 'Diskusikan Project', href: '/request-project' }"
                    :secondary-action="{ label: 'Lihat Layanan', href: '/services' }"
                />
            </div>
        </section>
    </PublicLayout>
</template>
