<script setup>
defineProps({ name: { type: String, required: true } });
</script>

<template>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <template v-if="name === 'dashboard'"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></template>
        <template v-else-if="name === 'homepage'"><path d="m3 11 9-8 9 8"/><path d="M5 10v11h14V10M9 21v-7h6v7"/></template>
        <template v-else-if="name === 'pages'"><path d="M6 3h9l4 4v14H6z"/><path d="M15 3v5h4M9 12h6M9 16h6"/></template>
        <template v-else-if="name === 'navigation'"><path d="M4 6h16M4 12h10M4 18h16"/><circle cx="18" cy="12" r="2"/></template>
        <template v-else-if="name === 'services'"><path d="M12 3 4 7v10l8 4 8-4V7zM4 7l8 4 8-4M12 11v10"/></template>
        <template v-else-if="name === 'projects'"><rect x="3" y="6" width="18" height="14" rx="2"/><path d="M8 6V4h8v2M3 11h18M10 11v2h4v-2"/></template>
        <template v-else-if="name === 'technology'"><rect x="4" y="4" width="16" height="16" rx="3"/><path d="M9 9h6v6H9zM9 1v3M15 1v3M9 20v3M15 20v3M1 9h3M20 9h3M1 15h3M20 15h3"/></template>
        <template v-else-if="name === 'articles'"><path d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5"/></template>
        <template v-else-if="name === 'categories'"><path d="M4 5h6l2 2h8v12H4z"/></template>
        <template v-else-if="name === 'testimonials'"><path d="M5 6h14v10H9l-4 4z"/><path d="M9 10h6M9 13h4"/></template>
        <template v-else-if="name === 'leads'"><circle cx="9" cy="8" r="4"/><path d="M3 21v-2a6 6 0 0 1 12 0v2M17 9h4M19 7v4"/></template>
        <template v-else-if="name === 'media'"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9" r="1.5"/><path d="m4 17 5-5 4 4 2-2 5 4"/></template>
        <template v-else-if="name === 'company'"><path d="M4 21V6l8-3 8 3v15M8 9h2M14 9h2M8 13h2M14 13h2M9 21v-4h6v4"/></template>
        <template v-else-if="name === 'seo'"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4M8 11h6M11 8v6"/></template>
        <template v-else-if="name === 'settings'"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-2.8 2.8-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6v.2h-4V21a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1L4.2 17l.1-.1a1.7 1.7 0 0 0 .3-1.9A1.7 1.7 0 0 0 3 14H2.8v-4H3a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9L4.2 7 7 4.2l.1.1a1.7 1.7 0 0 0 1.9.3A1.7 1.7 0 0 0 10 3v-.2h4V3a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1L19.8 7l-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.2v4H21a1.7 1.7 0 0 0-1.6 1Z"/></template>
        <template v-else-if="name === 'menu'"><path d="M4 6h16M4 12h16M4 18h16"/></template>
        <template v-else-if="name === 'close'"><path d="m6 6 12 12M18 6 6 18"/></template>
        <template v-else-if="name === 'search'"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></template>
        <template v-else-if="name === 'plus'"><path d="M12 5v14M5 12h14"/></template>
        <template v-else-if="name === 'edit'"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4z"/></template>
        <template v-else-if="name === 'trash'"><path d="M4 7h16M9 7V4h6v3M7 7l1 14h8l1-14M10 11v6M14 11v6"/></template>
        <template v-else-if="name === 'logout'"><path d="M10 5H5v14h5M14 8l4 4-4 4M8 12h10"/></template>
        <template v-else-if="name === 'chevron'"><path d="m9 18 6-6-6-6"/></template>
        <template v-else-if="name === 'info'"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/></template>
        <template v-else-if="name === 'check'"><path d="m5 12 4 4L19 6"/></template>
        <template v-else-if="name === 'warning'"><path d="M12 3 2.5 20h19zM12 9v4M12 17h.01"/></template>
        <template v-else><circle cx="12" cy="12" r="9"/></template>
    </svg>
</template>
