<script setup>
import { Link } from '@inertiajs/vue3';
import AdminIcon from './AdminIcon.vue';

const props = defineProps({ href: String, method: String, type: { type: String, default: 'button' }, variant: { type: String, default: 'primary' }, icon: String, disabled: Boolean });
const styles = {
    primary: 'bg-sch-blue text-white shadow-sm hover:bg-blue-700',
    secondary: 'border border-sch-border bg-white text-sch-navy hover:border-slate-300 hover:bg-slate-50',
    danger: 'border border-red-200 bg-white text-red-700 hover:bg-red-50',
    ghost: 'text-sch-muted hover:bg-slate-100 hover:text-sch-navy',
};
</script>

<template>
    <Link v-if="href" :href="href" :method="method" :as="method ? 'button' : 'a'" :disabled="disabled" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl px-4 text-sm font-semibold transition-colors disabled:cursor-not-allowed disabled:opacity-55" :class="styles[variant]">
        <AdminIcon v-if="icon" :name="icon" class="size-4 shrink-0"/><slot />
    </Link>
    <button v-else :type="type" :disabled="disabled" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl px-4 text-sm font-semibold transition-colors disabled:cursor-not-allowed disabled:opacity-55" :class="styles[variant]">
        <AdminIcon v-if="icon" :name="icon" class="size-4 shrink-0"/><slot />
    </button>
</template>
