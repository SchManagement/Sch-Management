<script setup>
import PublicIcon from './PublicIcon.vue';
import ResponsiveImage from './ResponsiveImage.vue';
import SmartLink from './SmartLink.vue';

defineProps({
    project: { type: Object, required: true },
    featured: Boolean,
    homepage: Boolean,
});
</script>

<template>
    <article
        class="group relative isolate flex min-h-[24rem] overflow-hidden border border-white/15 bg-sch-dark-blue"
        :class="[
            featured ? 'lg:min-h-[40rem]' : 'lg:min-h-[19.5rem]',
            homepage ? 'rounded-[0.25rem]' : 'rounded-sch-lg',
        ]"
        data-project-card
    >
        <ResponsiveImage
            :src="project.thumbnail"
            :alt="project.thumbnail ? `Tampilan project ${project.title}` : ''"
            width="960"
            height="640"
            sizes="(min-width: 1024px) 50vw, 100vw"
            class="absolute inset-0 -z-20 transition-transform duration-300 group-hover:scale-[1.03] motion-reduce:transform-none"
        />
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-sch-midnight via-sch-midnight/90 to-sch-midnight/35" aria-hidden="true"/>
        <div v-if="homepage" class="absolute inset-x-0 top-0 h-px origin-left scale-x-0 bg-cyan-300 transition-transform duration-300 group-hover:scale-x-100 motion-reduce:transition-none" aria-hidden="true"/>

        <div class="mt-auto w-full p-6 sm:p-8" :class="featured ? 'lg:p-10 xl:p-12' : ''">
            <div class="flex flex-wrap items-center gap-x-3 gap-y-2 text-xs font-bold uppercase tracking-[0.14em] text-cyan-300">
                <span v-if="project.category">{{ project.category.name }}</span>
                <span v-if="project.category && project.industry" class="size-1 rounded-full bg-white/40" aria-hidden="true"/>
                <span v-if="project.industry">{{ project.industry }}</span>
            </div>
            <h3 class="mt-3 max-w-2xl font-semibold tracking-[-0.035em] text-white" :class="featured ? 'text-3xl sm:text-4xl lg:text-5xl' : 'text-2xl'">
                {{ project.title }}
            </h3>
            <p v-if="project.short_description" class="mt-3 max-w-2xl text-sm leading-7 text-slate-300">
                {{ project.short_description }}
            </p>
            <ul v-if="project.technologies?.length" class="mt-5 flex flex-wrap gap-2" aria-label="Teknologi project">
                <li v-for="technology in project.technologies.slice(0, 4)" :key="technology.slug" class="rounded-full border border-white/15 px-3 py-1 text-xs font-semibold text-slate-200">
                    {{ technology.name }}
                </li>
            </ul>
            <SmartLink
                :href="`/portfolio/${project.slug}`"
                class="mt-6 inline-flex min-h-11 items-center gap-2 rounded-sch-sm text-sm font-bold text-white"
                :aria-label="`Lihat project ${project.title}`"
            >
                Lihat project
                <PublicIcon name="arrow-up-right" class="size-4 transition-transform duration-200 group-hover:-translate-y-0.5 group-hover:translate-x-0.5 motion-reduce:transform-none"/>
            </SmartLink>
        </div>
    </article>
</template>
