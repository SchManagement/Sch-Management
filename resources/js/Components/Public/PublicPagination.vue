<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    links: { type: Array, default: () => [] },
    from: Number,
    to: Number,
    total: Number,
    ariaLabel: { type: String, default: 'Paginasi' },
    itemLabel: { type: String, default: 'item' },
});

const label = (item, index) => {
    if (index === 0) return 'Sebelumnya';
    if (index === props.links.length - 1) return 'Berikutnya';

    return String(item.label).replace(/<[^>]*>/g, '').replace(/&[^;]+;/g, '').trim();
};
</script>

<template>
    <nav v-if="links.length > 3" :aria-label="ariaLabel" class="mt-12 border-t border-sch-border pt-6">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm leading-6 text-sch-muted">
                Menampilkan <span class="font-semibold text-sch-navy">{{ from }}–{{ to }}</span>
                dari <span class="font-semibold text-sch-navy">{{ total }}</span> {{ itemLabel }}
            </p>
            <div class="flex flex-wrap gap-2">
                <template v-for="(item, index) in links" :key="`${item.label}-${index}`">
                    <Link
                        v-if="item.url"
                        :href="item.url"
                        preserve-scroll
                        class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-sch-sm border px-3 text-sm font-bold transition-colors"
                        :class="item.active
                            ? 'border-sch-navy bg-sch-navy text-white'
                            : 'border-sch-border bg-white text-sch-muted hover:border-blue-200 hover:bg-sch-soft-blue hover:text-sch-navy'"
                        :aria-current="item.active ? 'page' : undefined"
                        :aria-label="index === 0 || index === links.length - 1 ? `${label(item, index)} halaman` : `Halaman ${label(item, index)}`"
                    >
                        {{ label(item, index) }}
                    </Link>
                    <span
                        v-else
                        class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-sch-sm border border-sch-border px-3 text-sm font-semibold text-slate-400"
                        aria-disabled="true"
                    >
                        {{ label(item, index) }}
                    </span>
                </template>
            </div>
        </div>
    </nav>
</template>
