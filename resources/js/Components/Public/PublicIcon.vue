<script setup>
defineProps({ name: { type: String, required: true } });
</script>

<template>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <template v-if="name === 'arrow-right'"><path d="M5 12h14M14 7l5 5-5 5"/></template>
        <template v-else-if="name === 'arrow-up-right'"><path d="M7 17 17 7M8 7h9v9"/></template>
        <template v-else-if="name === 'menu'"><path d="M4 7h16M4 12h16M4 17h16"/></template>
        <template v-else-if="name === 'close'"><path d="m6 6 12 12M18 6 6 18"/></template>
        <template v-else-if="name === 'chevron-right'"><path d="m9 18 6-6-6-6"/></template>
        <template v-else-if="name === 'chevron-down'"><path d="m6 9 6 6 6-6"/></template>
        <template v-else-if="name === 'search'"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></template>
        <template v-else-if="name === 'mail'"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></template>
        <template v-else-if="name === 'phone'"><path d="M7 3h3l1.5 4-2 1.5a15 15 0 0 0 6 6l1.5-2L21 14v3a4 4 0 0 1-4 4C9.3 20.1 3.9 14.7 3 7a4 4 0 0 1 4-4Z"/></template>
        <template v-else-if="name === 'linkedin'"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 10v7M8 7v.01M12 17v-4a3 3 0 0 1 6 0v4M12 10v7"/></template>
        <template v-else-if="name === 'instagram'"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/></template>
        <template v-else-if="name === 'facebook'"><path d="M14 8h4V3h-4a5 5 0 0 0-5 5v3H6v5h3v5h5v-5h4l1-5h-5z"/></template>
        <template v-else-if="name === 'x'"><path d="M4 4l16 16M20 4 4 20"/></template>
        <template v-else-if="name === 'check'"><path d="m5 12 4 4L19 6"/></template>
        <template v-else-if="name === 'alert'"><path d="M12 3 2.5 20h19zM12 9v4M12 17h.01"/></template>
        <template v-else-if="name === 'info'"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/></template>
        <template v-else-if="name === 'code'"><path d="m8 9-3 3 3 3M16 9l3 3-3 3M14 5l-4 14"/></template>
        <template v-else-if="name === 'target'"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/></template>
        <template v-else-if="name === 'gauge'"><path d="M3.34 19a10 10 0 1 1 17.32 0M12 14l4-4"/><circle cx="12" cy="14" r="1"/></template>
        <template v-else-if="name === 'globe'"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/></template>
        <template v-else-if="name === 'layers'"><path d="m12 3 9 5-9 5-9-5zM3 12l9 5 9-5M3 16l9 5 9-5"/></template>
        <template v-else-if="name === 'layout'"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M9 9v11"/></template>
        <template v-else-if="name === 'maintenance'"><path d="m14 6 4-3 3 3-3 4M5 21l8-8M14 6l4 4M3 7l4 4M7 3l4 4M4 20l-1-1 2-4 4 4z"/></template>
        <template v-else-if="name === 'server'"><rect x="3" y="4" width="18" height="6" rx="2"/><rect x="3" y="14" width="18" height="6" rx="2"/><path d="M7 7h.01M7 17h.01"/></template>
        <template v-else-if="name === 'support'"><path d="M4 13v-2a8 8 0 0 1 16 0v2M4 13a2 2 0 0 0 2 2h1v-6H6a2 2 0 0 0-2 2M20 13a2 2 0 0 1-2 2h-1V9h1a2 2 0 0 1 2 2M17 15v1a4 4 0 0 1-4 4h-2"/></template>
        <template v-else-if="name === 'shield-check'"><path d="M12 3 5 6v5c0 4.6 2.9 8.3 7 10 4.1-1.7 7-5.4 7-10V6z"/><path d="m9 12 2 2 4-5"/></template>
        <template v-else-if="name === 'users'"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></template>
        <template v-else-if="name === 'technology'"><rect x="7" y="7" width="10" height="10" rx="2"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/></template>
        <template v-else><circle cx="12" cy="12" r="9"/></template>
    </svg>
</template>
