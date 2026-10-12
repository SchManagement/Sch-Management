<script setup>
import PublicIcon from './PublicIcon.vue';
import ResponsiveImage from './ResponsiveImage.vue';
import SmartLink from './SmartLink.vue';

const props = defineProps({
    article: { type: Object, required: true },
    prominent: Boolean,
});

const publishedDate = props.article.published_at
    ? new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }).format(new Date(props.article.published_at))
    : null;
</script>

<template>
    <article class="group flex h-full flex-col border-t border-sch-border pt-5" :class="prominent ? 'sm:grid sm:grid-cols-[1.12fr_0.88fr] sm:gap-8 lg:block' : ''" data-article-card>
        <div v-if="article.cover_image" class="overflow-hidden bg-sch-surface" :class="prominent ? 'aspect-[16/10] rounded-[0.25rem] lg:aspect-[16/9]' : 'aspect-[16/10] rounded-sch-md'">
            <ResponsiveImage
                :src="article.cover_image"
                :alt="`Gambar sampul artikel ${article.title}`"
                width="720"
                height="450"
                sizes="(min-width: 1280px) 33vw, (min-width: 640px) 50vw, 100vw"
                class="transition-transform duration-300 group-hover:scale-[1.03] motion-reduce:transform-none"
            />
        </div>

        <div class="flex flex-1 flex-col" :class="article.cover_image ? (prominent ? 'pt-6 sm:pt-0 lg:pt-7' : 'pt-6') : 'pt-2'">
            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs font-bold uppercase tracking-[0.12em]">
                <span v-if="article.category" class="text-sch-blue">{{ article.category.name }}</span>
                <span v-if="article.category && publishedDate" class="size-1 rounded-full bg-slate-300" aria-hidden="true"/>
                <time v-if="publishedDate" :datetime="article.published_at" class="text-sch-muted">{{ publishedDate }}</time>
            </div>
            <h3 class="mt-3 font-semibold leading-snug tracking-[-0.03em] text-sch-navy" :class="prominent ? 'text-2xl sm:text-3xl' : 'text-xl'">
                {{ article.title }}
            </h3>
            <p v-if="article.excerpt" class="mt-3 line-clamp-3 text-sm leading-7 text-sch-muted">
                {{ article.excerpt }}
            </p>
            <p v-if="article.author" class="mt-4 text-xs font-semibold text-sch-muted">Oleh {{ article.author.name }}</p>
            <SmartLink
                :href="`/insights/${article.slug}`"
                class="mt-6 inline-flex min-h-11 items-center gap-2 self-start rounded-sch-sm text-sm font-bold text-sch-blue"
                :aria-label="`Baca artikel ${article.title}`"
            >
                Baca insight
                <PublicIcon name="arrow-right" class="size-4 transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transform-none"/>
            </SmartLink>
        </div>
    </article>
</template>
