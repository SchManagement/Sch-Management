<script setup>
import { computed, useId } from 'vue';
import FormLabel from './FormLabel.vue';
import FormError from './FormError.vue';

const model = defineModel({ type: [String, Number], default: '' });
const props = defineProps({ id: String, label: String, type: { type: String, default: 'text' }, name: String, placeholder: String, autocomplete: String, required: Boolean, disabled: Boolean, error: String, help: String });
const generatedId = useId();
const fieldId = computed(() => props.id || `public-input-${generatedId}`);
const describedBy = computed(() => [props.help ? `${fieldId.value}-help` : null, props.error ? `${fieldId.value}-error` : null].filter(Boolean).join(' ') || undefined);
</script>
<template><div><FormLabel v-if="label" :for-id="fieldId" :required="required">{{ label }}</FormLabel><input :id="fieldId" v-model="model" :type="type" :name="name" :placeholder="placeholder" :autocomplete="autocomplete" :required="required" :disabled="disabled" :aria-invalid="error ? 'true' : undefined" :aria-describedby="describedBy" class="min-h-12 w-full rounded-sch-md border bg-white px-4 text-base text-sch-ink shadow-sch-sm transition-[border-color,box-shadow] duration-150 placeholder:text-slate-400 focus:border-sch-blue focus:outline-none focus:ring-4 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-sch-muted" :class="error ? 'border-red-400' : 'border-sch-border'"/><p v-if="help" :id="`${fieldId}-help`" class="mt-2 text-sm leading-6 text-sch-muted">{{ help }}</p><FormError :id="`${fieldId}-error`" :message="error"/></div></template>
