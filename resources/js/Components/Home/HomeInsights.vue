<script setup>
import { computed } from 'vue';
import ArticleCard from '../Public/ArticleCard.vue';
import PublicButton from '../Public/PublicButton.vue';
import SectionHeading from '../Public/SectionHeading.vue';
import { contentText, contentUrl } from '../../Utils/homepageContent';

const props = defineProps({
    section: { type: Object, required: true },
    articles: { type: Array, default: () => [] },
});
const content = computed(() => props.section.content || {});
const leadArticle = computed(() => props.articles[0]);
const supportingArticles = computed(() => props.articles.slice(1, 3));
</script>

<template>
    <section v-if="articles.length" class="relative bg-white pb-12 pt-6 sm:pb-16 sm:pt-8 lg:pb-20 lg:pt-10" aria-labelledby="home-insights-title" data-home-section="insights">
        <div class="public-container">
            <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
                <SectionHeading
                    class="lg:col-span-8"
                    level="h2"
                    heading-id="home-insights-title"
                    :eyebrow="contentText(content.eyebrow, 'INSIGHT')"
                    :title="contentText(section.heading, 'Perspektif untuk Bisnis dan Teknologi Digital.')"
                    :description="contentText(content.description, 'Artikel praktis seputar pengembangan website, teknologi, pengalaman pengguna, dan digitalisasi bisnis.')"
                />
                <div class="lg:col-span-4 lg:justify-self-end">
                    <PublicButton :href="contentUrl(content.cta_url, '/insights')" variant="outline" arrow>
                        {{ contentText(content.cta_label, 'Lihat Semua Insight') }}
                    </PublicButton>
                </div>
            </div>

            <div class="mt-14 grid gap-10 lg:grid-cols-12 lg:gap-8" data-stagger="articles">
                <ArticleCard :article="leadArticle" prominent class="lg:col-span-7 lg:pr-8"/>
                <div v-if="supportingArticles.length" class="grid gap-10 lg:col-span-5" data-stagger="supporting-articles">
                    <ArticleCard v-for="article in supportingArticles" :key="article.id" :article="article"/>
                </div>
            </div>

            <div class="mt-12 flex items-center gap-4 text-[0.625rem] font-bold uppercase tracking-[0.2em] text-slate-400" aria-hidden="true">
                <span>Latest thinking</span>
                <span class="h-px flex-1 bg-sch-border"/>
                <span>Digital / Technology</span>
            </div>
        </div>
    </section>
</template>
