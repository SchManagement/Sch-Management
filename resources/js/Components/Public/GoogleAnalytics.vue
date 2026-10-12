<script setup>
import { onBeforeUnmount, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({ measurementId: { type: String, default: null } });
const page = usePage();

const track = () => {
    if (typeof window.gtag !== 'function') return;
    window.gtag('event', 'page_view', { page_path: page.url, page_location: window.location.href });
};

onMounted(() => {
    if (!/^G-[A-Z0-9]+$/.test(props.measurementId || '')) return;
    window[`ga-disable-${props.measurementId}`] = false;

    if (!window.__schAnalyticsId) {
        window.__schAnalyticsId = props.measurementId;
        window.dataLayer = window.dataLayer || [];
        window.gtag = function () { window.dataLayer.push(arguments); };
        window.gtag('js', new Date());
        window.gtag('config', props.measurementId, { send_page_view: false });
        const script = document.createElement('script');
        script.async = true;
        script.src = `https://www.googletagmanager.com/gtag/js?id=${encodeURIComponent(props.measurementId)}`;
        document.head.appendChild(script);
    }
    track();
});
onBeforeUnmount(() => {
    if (props.measurementId) window[`ga-disable-${props.measurementId}`] = true;
});

watch(() => page.url, track);
</script>

<template></template>
