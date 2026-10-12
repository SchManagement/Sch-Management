<script setup>
defineProps({ id: String, message: String });
</script>
<template><p v-if="message" :id="id" class="mt-2 flex items-start gap-2 text-sm font-semibold text-sch-danger" role="alert"><svg class="mt-0.5 size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 8v5m0 3h.01"/></svg><span>{{ message }}</span></p></template>
