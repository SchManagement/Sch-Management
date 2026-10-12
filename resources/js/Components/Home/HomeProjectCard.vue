<script setup>
import { computed } from 'vue';
import PublicIcon from '../Public/PublicIcon.vue';
import SmartLink from '../Public/SmartLink.vue';

const props = defineProps({
    project: { type: Object, required: true },
    featured: Boolean,
});

const title = computed(() => props.project.title
    .toLocaleLowerCase('id-ID')
    .replace(/(^|\s)\S/g, letter => letter.toLocaleUpperCase('id-ID')));
const category = computed(() => props.project.category?.name?.split('/').at(-1)?.trim() || 'Project Digital');
</script>

<template>
    <article class="group min-w-0 self-start overflow-hidden rounded-lg border border-white/10 bg-[#0D1826] transition-colors duration-200 hover:border-white/25 focus-within:border-[#3EC6FF]" data-project-card :data-featured="featured || undefined">
        <SmartLink v-if="featured" :href="`/portfolio/${project.slug}`" :aria-label="`Lihat project ${title}`" class="flex min-w-0 flex-col focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-[-3px] focus-visible:outline-[#3EC6FF]">
            <img v-if="project.thumbnail" :src="project.thumbnail" :alt="`Tampilan project ${title}`" loading="lazy" decoding="async" class="block h-auto w-full"/>
            <div class="max-w-lg p-6 sm:p-8 xl:p-9">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#3EC6FF]">{{ category }}</p>
                <h3 class="mt-3 text-3xl font-semibold leading-tight tracking-[-0.035em] text-[#F8FAFC] sm:text-[2.125rem]">{{ title }}</h3>
                <p v-if="project.short_description" class="mt-3 line-clamp-2 text-sm leading-6 text-[#B8C3D1]">{{ project.short_description }}</p>
                <span class="mt-6 inline-flex min-h-11 items-center gap-2 text-sm font-semibold text-[#F8FAFC]">
                    Lihat Detail
                    <PublicIcon name="arrow-right" class="size-4 text-[#3EC6FF] transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transform-none"/>
                </span>
            </div>
        </SmartLink>

        <SmartLink v-else :href="`/portfolio/${project.slug}`" :aria-label="`Lihat project ${title}`" class="flex min-w-0 flex-col focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-[-3px] focus-visible:outline-[#3EC6FF]">
            <img v-if="project.thumbnail" :src="project.thumbnail" :alt="`Tampilan project ${title}`" loading="lazy" decoding="async" class="block h-auto w-full"/>
            <div class="flex min-w-0 flex-1 items-center gap-3 p-5 xl:p-5">
                <div class="min-w-0 flex-1">
                    <p class="text-[0.6875rem] font-bold uppercase tracking-[0.12em] text-[#3EC6FF]">{{ category }}</p>
                    <h3 class="mt-2 text-xl font-semibold leading-tight tracking-[-0.025em] text-[#F8FAFC]">{{ title }}</h3>
                    <p v-if="project.short_description" class="mt-2 line-clamp-2 text-sm leading-5 text-[#B8C3D1]">{{ project.short_description }}</p>
                </div>
                <span class="grid size-11 shrink-0 place-items-center rounded-full border border-white/20 text-[#3EC6FF] transition-colors duration-200 group-hover:border-[#3EC6FF]" aria-hidden="true">
                    <PublicIcon name="arrow-up-right" class="size-4 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 motion-reduce:transform-none"/>
                </span>
            </div>
        </SmartLink>
    </article>
</template>
