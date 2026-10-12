<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import PublicIcon from './PublicIcon.vue';

const props = defineProps({
    href: String,
    type: { type: String, default: 'button' },
    variant: { type: String, default: 'primary' },
    arrow: Boolean,
    loading: Boolean,
    disabled: Boolean,
    target: String,
});

const external = computed(() => props.href && /^(https?:|mailto:|tel:)/i.test(props.href));
const classes = computed(() => ({
    primary: 'bg-sch-blue text-white shadow-sch-blue hover:bg-blue-700 active:bg-blue-800',
    secondary: 'bg-sch-navy text-white shadow-sch-sm hover:bg-sch-dark-blue active:bg-sch-midnight',
    outline: 'border border-sch-border bg-white text-sch-navy shadow-sch-sm hover:border-blue-200 hover:bg-sch-soft-blue',
    ghost: 'text-sch-navy hover:bg-slate-100',
    light: 'bg-white text-sch-navy shadow-sch-md hover:bg-sch-soft-blue',
    inverted: 'border border-white/25 bg-transparent text-white hover:border-white/40 hover:bg-white/10',
}[props.variant]));
</script>

<template>
    <a v-if="href && external" :href="href" :target="target" :rel="target === '_blank' ? 'noopener noreferrer' : undefined" :tabindex="disabled || loading ? -1 : undefined" class="group public-button" :class="classes" :aria-disabled="disabled || loading ? 'true' : undefined" @click="(disabled || loading) && $event.preventDefault()"><slot/><PublicIcon v-if="arrow" name="arrow-right" class="size-4 transition-transform duration-200 group-hover:translate-x-0.5 motion-reduce:group-hover:translate-x-0"/></a>
    <Link v-else-if="href" :href="href" :target="target" :tabindex="disabled || loading ? -1 : undefined" class="group public-button" :class="classes" :aria-disabled="disabled || loading ? 'true' : undefined" @click="(disabled || loading) && $event.preventDefault()"><slot/><PublicIcon v-if="arrow" name="arrow-right" class="size-4 transition-transform duration-200 group-hover:translate-x-0.5 motion-reduce:group-hover:translate-x-0"/></Link>
    <button v-else :type="type" :disabled="disabled || loading" :aria-busy="loading ? 'true' : undefined" class="group public-button" :class="classes"><span v-if="loading" class="size-4 animate-spin rounded-full border-2 border-current border-r-transparent motion-reduce:animate-none" aria-hidden="true"/><slot/><PublicIcon v-if="arrow && !loading" name="arrow-right" class="size-4 transition-transform duration-200 group-hover:translate-x-0.5 motion-reduce:group-hover:translate-x-0"/></button>
</template>

<style scoped>
.public-button {
    display: inline-flex;
    min-height: 3rem;
    align-items: center;
    justify-content: center;
    gap: 0.625rem;
    border-radius: var(--radius-sch-md);
    padding: 0.75rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 700;
    line-height: 1.25rem;
    transition: color var(--sch-duration-fast), background-color var(--sch-duration-fast), border-color var(--sch-duration-fast), box-shadow var(--sch-duration-fast), transform var(--sch-duration-fast);
}
.public-button:active { transform: scale(0.98); }
.public-button[aria-disabled='true'], .public-button:disabled { cursor: not-allowed; opacity: 0.48; }
@media (prefers-reduced-motion: reduce) { .public-button:active { transform: none; } }
</style>
