<script setup>
defineProps({ dark: Boolean });
</script>
<template><span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.16em]" :class="dark ? 'text-cyan-300' : 'text-sch-blue'"><span class="h-px w-6 bg-current" aria-hidden="true"/><slot/></span></template>
