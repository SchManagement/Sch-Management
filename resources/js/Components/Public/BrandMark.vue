<script setup>
defineProps({
    name: { type: String, default: 'SCH Management' },
    tagline: { type: String, default: 'WEB SOLUTIONS | IT SERVICES | DIGITAL GROWTH' },
    logo: String,
    light: Boolean,
    compact: Boolean,
    horizontal: Boolean,
});
</script>

<template>
    <span v-if="horizontal" class="relative block h-[2.625rem] w-[9.7rem] shrink-0 sm:h-12 sm:w-44 lg:h-[3.25rem] lg:w-48">
        <img :src="logo" :alt="name" width="1448" height="1086" class="pointer-events-none absolute left-0 top-1/2 h-[7.25rem] w-auto max-w-none -translate-y-1/2 object-contain object-left sm:h-[8.25rem] lg:h-36"/>
        <img v-if="light" :src="logo" alt="" width="1448" height="1086" aria-hidden="true" class="pointer-events-none absolute left-0 top-1/2 h-[7.25rem] w-auto max-w-none -translate-y-1/2 object-contain object-left brightness-0 invert sm:h-[8.25rem] lg:h-36" style="clip-path: inset(0 0 0 28%)"/>
    </span>
    <span v-else class="inline-flex min-w-0 items-center gap-3">
        <img v-if="logo" :src="logo" alt="" width="44" height="44" class="size-11 shrink-0 object-contain" aria-hidden="true"/>
        <span v-else class="h-8 w-1 shrink-0 rounded-full bg-sch-blue" aria-hidden="true"/>
        <span v-if="!compact" class="min-w-0 leading-none">
            <span class="block truncate text-[0.95rem] font-extrabold tracking-[-0.02em]" :class="light ? 'text-white' : 'text-sch-navy'">{{ name }}</span>
            <span class="mt-1.5 block max-w-44 truncate text-[0.58rem] font-bold uppercase tracking-[0.15em]" :class="light ? 'text-slate-400' : 'text-sch-muted'">{{ tagline }}</span>
        </span>
    </span>
</template>
