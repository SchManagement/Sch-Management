<script setup>
import { computed } from 'vue';
import ArticleCard from '../../Components/Public/ArticleCard.vue';
import Breadcrumb from '../../Components/Public/Breadcrumb.vue';
import PublicButton from '../../Components/Public/PublicButton.vue';
import PublicCTA from '../../Components/Public/PublicCTA.vue';
import ResponsiveImage from '../../Components/Public/ResponsiveImage.vue';
import PublicLayout from '../../Layouts/PublicLayout.vue';

const props = defineProps({
    article: { type: Object, required: true },
    relatedArticles: { type: Array, default: () => [] },
});

const formatter = new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
const publishedDate = computed(() => props.article.published_at ? formatter.format(new Date(props.article.published_at)) : null);
const updatedDate = computed(() => {
    if (!props.article.published_at || !props.article.updated_at) return null;

    const published = new Date(props.article.published_at);
    const updated = new Date(props.article.updated_at);

    return updated.getTime() - published.getTime() >= 86400000 ? formatter.format(updated) : null;
});
const articleBlocks = computed(() => (props.article.content || '')
    .trim()
    .split(/\n\s*\n/u)
    .map(block => {
        const lines = block.trim().split(/\r?\n/u);
        if (lines.length > 1 && lines.every(line => /^[-*]\s+\S/u.test(line))) {
            return { type: 'ul', items: lines.map(line => line.replace(/^[-*]\s+/u, '')) };
        }
        if (lines.length > 1 && lines.every(line => /^\d+\.\s+\S/u.test(line))) {
            return { type: 'ol', items: lines.map(line => line.replace(/^\d+\.\s+/u, '')) };
        }
        if (/^[-*]\s+\S/u.test(block)) {
            return { type: 'ul', items: lines.map(line => line.replace(/^[-*]\s+/u, '')) };
        }
        if (/^###\s+\S/u.test(block)) return { type: 'h3', text: block.replace(/^###\s+/u, '') };
        if (/^##\s+\S/u.test(block)) return { type: 'h2', text: block.replace(/^##\s+/u, '') };
        if (lines.length === 1 && /^\d+\.\s+\S/u.test(block)) return { type: 'h2', text: block };
        return { type: 'p', text: block };
    })
    .filter(block => block.text || block.items?.length));
</script>

<template>
    <PublicLayout>
        <article>
            <header class="sch-article-hero relative isolate overflow-hidden border-b border-sch-border bg-white pb-24 pt-14 sm:pb-28 sm:pt-20 lg:pb-32 lg:pt-24" data-reveal>
                <div class="pointer-events-none absolute -right-24 top-0 -z-10 size-96 rounded-full bg-sch-soft-blue blur-3xl" aria-hidden="true"/>
                <div class="public-container">
                    <Breadcrumb :items="[
                        { label: 'Beranda', href: '/' },
                        { label: 'Insight', href: '/insights' },
                        { label: article.title, current: true },
                    ]"/>

                    <div class="sch-article-intro mt-12 grid gap-10 lg:grid-cols-[minmax(0,1.55fr)_minmax(16rem,0.65fr)] lg:items-end lg:gap-16">
                        <div>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs font-bold uppercase tracking-[0.16em]">
                                <span v-if="article.category" class="text-sch-blue">{{ article.category.name }}</span>
                                <span v-if="article.category && publishedDate" class="h-px w-8 bg-sch-blue/50" aria-hidden="true"/>
                                <time v-if="publishedDate" :datetime="article.published_at" class="text-sch-muted">{{ publishedDate }}</time>
                            </div>
                            <h1 class="public-heading mt-6 max-w-[20ch] text-sch-navy">{{ article.title }}</h1>
                        </div>
                        <div class="border-t border-sch-border pt-6 lg:mb-2">
                            <p v-if="article.excerpt" class="text-base leading-8 text-sch-muted sm:text-lg">{{ article.excerpt }}</p>
                            <div v-if="article.author || updatedDate" class="mt-7 flex flex-wrap items-center gap-x-4 gap-y-2 border-t border-sch-border pt-5 text-sm text-sch-muted">
                                <span v-if="article.author">Oleh <strong class="font-bold text-sch-navy">{{ article.author.name }}</strong></span>
                                <time v-if="updatedDate" :datetime="article.updated_at">Diperbarui {{ updatedDate }}</time>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div v-if="article.cover_image" class="mt-5 sm:mt-6 lg:mt-7">
                <div class="public-container">
                    <div class="sch-article-cover mx-auto aspect-[16/9] max-w-6xl overflow-hidden bg-sch-surface" data-image-reveal>
                        <ResponsiveImage
                            :src="article.cover_image"
                            :alt="`Gambar sampul artikel ${article.title}`"
                            width="1440"
                            height="810"
                            loading="eager"
                            sizes="(min-width: 1280px) 1152px, 100vw"
                        />
                    </div>
                </div>
            </div>

            <section v-if="article.content" class="bg-white pb-16 pt-14 sm:pb-20 sm:pt-20 lg:pb-24 lg:pt-24" aria-labelledby="article-content-title">
                <div class="public-container">
                    <h2 id="article-content-title" class="sr-only">Isi artikel</h2>
                    <div class="sch-article-body mx-auto max-w-[760px]">
                        <template v-for="(block, index) in articleBlocks" :key="index">
                            <h2 v-if="block.type === 'h2'">{{ block.text }}</h2>
                            <h3 v-else-if="block.type === 'h3'">{{ block.text }}</h3>
                            <ul v-else-if="block.type === 'ul'"><li v-for="(item, itemIndex) in block.items" :key="itemIndex">{{ item }}</li></ul>
                            <ol v-else-if="block.type === 'ol'"><li v-for="(item, itemIndex) in block.items" :key="itemIndex">{{ item }}</li></ol>
                            <p v-else class="whitespace-pre-line">{{ block.text }}</p>
                        </template>
                    </div>
                </div>
            </section>

            <section v-if="relatedArticles.length" class="bg-sch-surface py-16 sm:py-20 lg:py-24" aria-labelledby="related-articles-title">
                <div class="public-container">
                    <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                        <div class="max-w-3xl">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">RELATED INSIGHTS</p>
                            <h2 id="related-articles-title" class="public-subheading mt-4">Insight Lain dalam Topik Terkait.</h2>
                        </div>
                        <PublicButton href="/insights" variant="outline">Lihat Semua Insight</PublicButton>
                    </div>
                    <div class="mt-10 grid gap-x-6 gap-y-12 sm:grid-cols-2 xl:grid-cols-3" data-stagger="related-articles">
                        <ArticleCard v-for="item in relatedArticles" :key="item.id" :article="item"/>
                    </div>
                </div>
            </section>

            <section class="bg-white py-20 sm:py-24 lg:py-28">
                <div class="public-container">
                    <PublicCTA
                        heading="Perlu menerapkan solusi digital untuk bisnis Anda?"
                        description="Diskusikan kebutuhan website, sistem digital, atau dukungan teknologi berdasarkan konteks dan tujuan bisnis Anda."
                        :primary-action="{ label: 'Diskusikan Project', href: '/request-project' }"
                        :secondary-action="{ label: 'Lihat Layanan', href: '/services' }"
                    />
                </div>
            </section>
        </article>
    </PublicLayout>
</template>
