<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
const props = defineProps({ href: { type: String, required: true }, target: String });
const external = computed(() => /^(https?:|mailto:|tel:)/i.test(props.href));
</script>
<template><a v-if="external" :href="href" :target="target" :rel="target === '_blank' ? 'noopener noreferrer' : undefined"><slot/></a><Link v-else :href="href" :target="target" :rel="target === '_blank' ? 'noopener noreferrer' : undefined"><slot/></Link></template>
