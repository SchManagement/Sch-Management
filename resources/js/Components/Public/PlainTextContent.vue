<script setup>
import { computed } from 'vue';

const props = defineProps({
    text: String,
    lead: Boolean,
    dark: Boolean,
});

const paragraphs = computed(() => typeof props.text === 'string'
    ? props.text
        .trim()
        .split(/\n\s*\n/u)
        .map(paragraph => paragraph.trim())
        .filter(Boolean)
    : []);
</script>

<template>
    <div v-if="paragraphs.length" class="space-y-5">
        <p
            v-for="(paragraph, index) in paragraphs"
            :key="index"
            class="whitespace-pre-line"
            :class="[lead ? 'text-lg leading-8' : 'text-base leading-8', dark ? 'text-slate-300' : 'text-sch-muted']"
        >
            {{ paragraph }}
        </p>
    </div>
</template>
