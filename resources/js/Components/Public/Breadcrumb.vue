<script setup>
import { Link } from '@inertiajs/vue3';
import PublicIcon from './PublicIcon.vue';
defineProps({ items: { type: Array, default: () => [] }, dark: Boolean });
</script>
<template>
    <nav aria-label="Breadcrumb" class="min-w-0">
        <ol class="flex min-h-11 min-w-0 items-center gap-1 text-sm">
            <li v-for="(item, index) in items" :key="`${item.label}-${index}`" class="flex items-center gap-1" :class="item.current ? 'min-w-0' : 'shrink-0'">
                <PublicIcon v-if="index" name="chevron-right" class="size-4 shrink-0" :class="dark ? 'text-slate-500' : 'text-slate-400'"/>
                <span v-if="item.current || !item.href" aria-current="page" class="block min-w-0 max-w-[min(52vw,14rem)] truncate font-semibold sm:max-w-none sm:whitespace-normal sm:wrap-anywhere" :title="item.label" :class="dark ? 'text-white' : 'text-sch-navy'">{{ item.label }}</span>
                <Link v-else :href="item.href" class="inline-flex min-h-11 shrink-0 items-center whitespace-nowrap rounded-md px-1 transition-colors" :class="dark ? 'text-slate-300 hover:text-white' : 'text-sch-muted hover:text-sch-blue'">{{ item.label }}</Link>
            </li>
        </ol>
    </nav>
</template>
