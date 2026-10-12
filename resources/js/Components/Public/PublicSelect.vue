<script setup>
import { computed, useId } from 'vue';
import FormLabel from './FormLabel.vue';
import FormError from './FormError.vue';
const model = defineModel({ type: [String, Number], default: '' });
const props = defineProps({ id: String, label: String, name: String, options: { type: Array, default: () => [] }, placeholder: { type: String, default: 'Pilih opsi' }, required: Boolean, disabled: Boolean, error: String, help: String });
const generatedId = useId();
const fieldId = computed(() => props.id || `public-select-${generatedId}`);
const describedBy = computed(() => [props.help ? `${fieldId.value}-help` : null, props.error ? `${fieldId.value}-error` : null].filter(Boolean).join(' ') || undefined);
</script>
<template><div><FormLabel v-if="label" :for-id="fieldId" :required="required">{{ label }}</FormLabel><select :id="fieldId" v-model="model" :name="name" :required="required" :disabled="disabled" :aria-invalid="error ? 'true' : undefined" :aria-describedby="describedBy" class="min-h-12 w-full rounded-sch-md border bg-white px-4 text-base text-sch-ink shadow-sch-sm focus:border-sch-blue focus:outline-none focus:ring-4 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-slate-100" :class="error ? 'border-red-400' : 'border-sch-border'"><option value="" disabled>{{ placeholder }}</option><option v-for="option in options" :key="String(option.value)" :value="option.value">{{ option.label }}</option></select><p v-if="help" :id="`${fieldId}-help`" class="mt-2 text-sm leading-6 text-sch-muted">{{ help }}</p><FormError :id="`${fieldId}-error`" :message="error"/></div></template>
