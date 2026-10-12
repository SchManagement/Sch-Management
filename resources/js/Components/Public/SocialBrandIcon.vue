<script setup>
import { useId } from 'vue';

defineProps({ name: { type: String, required: true } });

const instagramGradientId = `instagram-gradient-${useId().replaceAll(':', '')}`;
</script>

<template>
    <svg v-if="name === 'linkedin'" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M20.45 20.45h-3.56v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.34V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28ZM5.32 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12ZM7.1 20.45H3.54V9H7.1v11.45Z"/>
    </svg>

    <svg v-else-if="name === 'instagram'" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <defs>
            <linearGradient :id="instagramGradientId" x1="3" y1="21" x2="21" y2="3" gradientUnits="userSpaceOnUse">
                <stop stop-color="#FCAF45"/>
                <stop offset="0.35" stop-color="#F77737"/>
                <stop offset="0.68" stop-color="#E1306C"/>
                <stop offset="1" stop-color="#833AB4"/>
            </linearGradient>
        </defs>
        <rect x="3" y="3" width="18" height="18" rx="5" :stroke="`url(#${instagramGradientId})`" stroke-width="2.2"/>
        <circle cx="12" cy="12" r="4.1" :stroke="`url(#${instagramGradientId})`" stroke-width="2.2"/>
        <circle cx="17.4" cy="6.7" r="1.15" :fill="`url(#${instagramGradientId})`"/>
    </svg>

    <svg v-else-if="name === 'whatsapp'" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48a9.02 9.02 0 0 1-1.66-2.07c-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.61-.92-2.2-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.07 2.88 1.21 3.08.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.19 1.87.12.57-.08 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35Zm-5.39 7.44h-.01a9.8 9.8 0 0 1-4.99-1.37l-.36-.21-3.71.97.99-3.62-.23-.37a9.78 9.78 0 0 1-1.5-5.23A9.82 9.82 0 0 1 12.09 2a9.82 9.82 0 0 1 9.81 9.99 9.82 9.82 0 0 1-9.82 9.83Zm8.35-18.17A11.78 11.78 0 0 0 12.1.2 11.8 11.8 0 0 0 .31 12c0 2.08.54 4.1 1.57 5.87L.21 24l6.27-1.64a11.8 11.8 0 0 0 5.61 1.43h.01A11.8 11.8 0 0 0 23.89 12c0-3.15-1.23-6.11-3.46-8.35Z"/>
    </svg>
</template>
