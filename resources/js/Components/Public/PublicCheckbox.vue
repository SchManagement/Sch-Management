<script setup>
import { computed, useId } from 'vue';
import FormError from './FormError.vue';
const model = defineModel({ type: Boolean, default: false });
const props = defineProps({ id: String, label: String, name: String, required: Boolean, disabled: Boolean, error: String, help: String });
const generatedId = useId();
const fieldId = computed(() => props.id || `public-checkbox-${generatedId}`);
const describedBy = computed(() => [props.help ? `${fieldId.value}-help` : null, props.error ? `${fieldId.value}-error` : null].filter(Boolean).join(' ') || undefined);
</script>
<template><div><label :for="fieldId" class="flex min-h-12 cursor-pointer items-start gap-3 rounded-sch-md border bg-white px-4 py-3 transition-colors hover:bg-slate-50" :class="[error ? 'border-red-400' : 'border-sch-border', disabled ? 'cursor-not-allowed opacity-50' : '']"><input :id="fieldId" v-model="model" type="checkbox" :name="name" :required="required" :disabled="disabled" :aria-invalid="error ? 'true' : undefined" :aria-describedby="describedBy" class="mt-0.5 size-5 rounded border-slate-300 text-sch-blue focus:ring-sch-blue"/><span class="text-sm leading-6 text-sch-ink">{{ label }}<span v-if="required" class="ml-1 text-sch-danger" aria-hidden="true">*</span><span v-if="required" class="sr-only"> (wajib)</span></span></label><p v-if="help" :id="`${fieldId}-help`" class="mt-2 text-sm leading-6 text-sch-muted">{{ help }}</p><FormError :id="`${fieldId}-error`" :message="error"/></div></template>
