<script setup>
import { computed } from 'vue';

const props = defineProps({ field: { type: Object, required: true }, modelValue: { required: false }, error: String });
const emit = defineEmits(['update:modelValue']);
const id = computed(() => `field-${props.field.name.replaceAll('.', '-')}`);
const describedBy = computed(() => [props.field.help ? `${id.value}-help` : '', props.error ? `${id.value}-error` : ''].filter(Boolean).join(' ') || undefined);
const base = 'min-h-11 w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm text-sch-ink shadow-sm transition-colors placeholder:text-slate-400 focus:border-sch-blue focus:outline-none focus:ring-3 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-slate-100';
</script>
<template>
    <div :class="field.span === 2 ? 'md:col-span-2' : ''">
        <label v-if="field.type !== 'checkbox'" :for="id" class="mb-2 block text-sm font-semibold text-sch-navy">{{ field.label }} <span v-if="field.required" class="text-red-600" aria-hidden="true">*</span></label>
        <textarea v-if="field.type === 'textarea'" :id="id" :value="modelValue" :rows="field.rows || 4" :required="field.required" :maxlength="field.maxlength" :aria-invalid="error ? 'true' : undefined" :aria-describedby="describedBy" :class="[base, error ? 'border-red-400 ring-2 ring-red-50' : 'border-sch-border']" @input="emit('update:modelValue', $event.target.value)"/>
        <select v-else-if="field.type === 'select'" :id="id" :value="modelValue ?? ''" :required="field.required" :aria-invalid="error ? 'true' : undefined" :aria-describedby="describedBy" :class="[base, error ? 'border-red-400' : 'border-sch-border']" @change="emit('update:modelValue', $event.target.value)"><option value="">Pilih {{ field.label.toLowerCase() }}</option><option v-for="option in field.options || []" :key="String(option.value)" :value="option.value">{{ option.label }}</option></select>
        <select v-else-if="field.type === 'multiselect'" :id="id" multiple :aria-invalid="error ? 'true' : undefined" :aria-describedby="describedBy" :class="[base, 'min-h-36', error ? 'border-red-400' : 'border-sch-border']" @change="emit('update:modelValue', Array.from($event.target.selectedOptions, option => Number(option.value)))"><option v-for="option in field.options || []" :key="String(option.value)" :value="option.value" :selected="(modelValue || []).map(String).includes(String(option.value))">{{ option.label }}</option></select>
        <label v-else-if="field.type === 'checkbox'" :for="id" class="flex min-h-11 cursor-pointer items-start gap-3 rounded-xl border bg-white px-4 py-3 transition-colors hover:bg-slate-50" :class="error ? 'border-red-400' : 'border-sch-border'"><input :id="id" type="checkbox" :checked="Boolean(modelValue)" class="mt-0.5 size-5 rounded border-slate-300 text-sch-blue focus:ring-sch-blue" :aria-invalid="error ? 'true' : undefined" :aria-describedby="describedBy" @change="emit('update:modelValue', $event.target.checked)"/><span class="text-sm font-semibold leading-5 text-sch-navy">{{ field.label }}</span></label>
        <input v-else :id="id" :type="field.type || 'text'" :value="modelValue ?? ''" :required="field.required" :min="field.min" :max="field.max" :maxlength="field.maxlength" :aria-invalid="error ? 'true' : undefined" :aria-describedby="describedBy" :class="[base, error ? 'border-red-400 ring-2 ring-red-50' : 'border-sch-border']" @input="emit('update:modelValue', $event.target.value)"/>
        <p v-if="field.help" :id="`${id}-help`" class="mt-1.5 text-xs leading-5 text-sch-muted">{{ field.help }}</p>
        <p v-if="error" :id="`${id}-error`" class="mt-1.5 text-sm font-medium text-red-700">{{ error }}</p>
    </div>
</template>
