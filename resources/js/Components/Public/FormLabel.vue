<script setup>
defineProps({ forId: String, required: Boolean });
</script>
<template><label :for="forId" class="mb-2 block text-sm font-bold text-sch-navy"><slot/><span v-if="required" class="ml-1 text-sch-danger" aria-hidden="true">*</span><span v-if="required" class="sr-only"> (wajib)</span></label></template>
