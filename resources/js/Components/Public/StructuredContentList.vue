<script setup>
import PublicIcon from './PublicIcon.vue';

defineProps({
    items: { type: Array, default: () => [] },
    ordered: Boolean,
    columns: Boolean,
    ariaLabel: String,
});
</script>

<template>
    <component
        :is="ordered ? 'ol' : 'ul'"
        v-if="items.length"
        class="grid border-t border-sch-border"
        :class="columns && items.length > 1 ? 'md:grid-cols-2 md:gap-x-10' : ''"
        :aria-label="ariaLabel"
    >
        <li
            v-for="(item, index) in items"
            :key="`${item.title}-${index}`"
            class="flex gap-4 border-b border-sch-border py-5 sm:gap-5"
        >
            <span v-if="ordered" class="mt-0.5 font-mono text-sm font-bold text-sch-blue" aria-hidden="true">
                {{ index + 1 }}
            </span>
            <span v-else class="mt-0.5 grid size-6 shrink-0 place-items-center rounded-full bg-sch-soft-blue text-sch-blue" aria-hidden="true">
                <PublicIcon name="check" class="size-3.5"/>
            </span>
            <div class="min-w-0">
                <h3 class="font-bold leading-6 text-sch-navy">{{ item.title }}</h3>
                <p v-if="item.description" class="mt-2 text-sm leading-7 text-sch-muted">{{ item.description }}</p>
            </div>
        </li>
    </component>
</template>
