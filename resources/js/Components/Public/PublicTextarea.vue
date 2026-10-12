<script setup>
import { computed, useId } from 'vue';
import FormLabel from './FormLabel.vue';
import FormError from './FormError.vue';
const model = defineModel({ type: String, default: '' });
const props = defineProps({ id: String, label: String, name: String, placeholder: String, rows: { type: Number, default: 5 }, required: Boolean, disabled: Boolean, error: String, help: String, maxlength: Number });
const generatedId = useId();
const fieldId = computed(() => props.id || `public-textarea-${generatedId}`);
const describedBy = computed(() => [props.help ? `${fieldId.value}-help` : null, props.error ? `${fieldId.value}-error` : null].filter(Boolean).join(' ') || undefined);
</script>
<template><div><FormLabel v-if="label" :for-id="fieldId" :required="required">{{ label }}</FormLabel><textarea :id="fieldId" v-model="model" :name="name" :placeholder="placeholder" :rows="rows" :required="required" :disabled="disabled" :maxlength="maxlength" :aria-invalid="error ? 'true' : undefined" :aria-describedby="describedBy" class="w-full resize-y rounded-sch-md border bg-white px-4 py-3 text-base leading-7 text-sch-ink shadow-sch-sm focus:border-sch-blue focus:outline-none focus:ring-4 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-slate-100" :class="error ? 'border-red-400' : 'border-sch-border'"/><p v-if="help" :id="`${fieldId}-help`" class="mt-2 text-sm leading-6 text-sch-muted">{{ help }}</p><FormError :id="`${fieldId}-error`" :message="error"/></div></template>
