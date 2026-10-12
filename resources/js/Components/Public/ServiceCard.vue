<script setup>
import PublicIcon from './PublicIcon.vue';
import SmartLink from './SmartLink.vue';

defineProps({
    service: { type: Object, required: true },
    index: { type: Number, default: 0 },
    editorial: Boolean,
    tail: Boolean,
});

const supportedIcons = new Set([
    'code', 'gauge', 'globe', 'layers', 'layout', 'maintenance', 'server', 'support', 'target', 'technology',
]);
const serviceIcons = {
    'website-company-profile': 'layout',
    'landing-page': 'target',
    'website-custom-sistem-informasi': 'code',
    'maintenance-optimasi-website': 'gauge',
    'pengembangan-backend-admin-cms': 'server',
    'integrasi-pengembangan-fitur-website': 'layers',
};
const iconName = service => serviceIcons[service.slug]
    || (supportedIcons.has(service.icon) ? service.icon : 'layers');
</script>

<template>
    <article
        class="group relative flex h-full flex-col overflow-hidden bg-white transition-[background-color,border-color] duration-200"
        :class="[editorial ? 'min-h-[19rem] border-b border-r border-sch-border px-6 py-7 hover:bg-sch-soft-blue/45 sm:px-8 sm:py-9' : 'border-b border-sch-border px-5 py-7 hover:bg-white sm:p-8', index === 0 ? 'sm:col-span-2 xl:col-span-2' : '', tail ? 'xl:col-span-3 xl:min-h-0 xl:grid xl:grid-cols-[4rem_0.8fr_1.2fr_auto] xl:items-center xl:gap-8' : '']"
    >
        <span v-if="editorial" class="absolute inset-x-0 top-0 h-0.5 origin-left scale-x-0 bg-sch-blue transition-transform duration-200 group-hover:scale-x-100 motion-reduce:transition-none" aria-hidden="true"/>
        <div class="flex items-start justify-between gap-5">
            <PublicIcon :name="iconName(service)" class="service-card-icon mt-0.5 size-[1.625rem] shrink-0 text-sch-blue"/>
            <span class="font-mono text-xs font-bold tracking-[0.16em]" :class="editorial ? 'text-sch-blue' : 'text-slate-400'" aria-hidden="true">
                {{ index + 1 }}
            </span>
        </div>

        <h3 class="mt-8 font-semibold tracking-[-0.03em] text-sch-navy" :class="[index === 0 ? 'max-w-[22ch] text-2xl sm:text-3xl' : 'text-xl sm:text-2xl', tail ? 'xl:mt-0' : '']">
            {{ service.name }}
        </h3>
        <p v-if="service.short_description" class="mt-4 max-w-2xl flex-1 text-sm leading-7 text-sch-muted" :class="tail ? 'xl:mt-0' : ''">
            {{ service.short_description }}
        </p>

        <SmartLink
            :href="`/services/${service.slug}`"
            class="mt-8 inline-flex min-h-11 items-center gap-3 self-start rounded-sch-sm text-sm font-bold text-sch-blue"
            :class="tail ? 'xl:mt-0 xl:self-center' : ''"
            :aria-label="`Pelajari layanan ${service.name}`"
        >
            Pelajari layanan
            <PublicIcon name="arrow-right" class="size-4 transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transform-none"/>
        </SmartLink>
    </article>
</template>
