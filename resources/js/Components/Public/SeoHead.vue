<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({ seo: { type: Object, required: true } });
// JSON_HEX_TAG equivalent: no CMS text can close the script element.
const jsonLd = computed(() => (props.seo.structuredData || []).map(item => JSON.stringify(item)
    .replace(/</g, '\\u003c').replace(/>/g, '\\u003e').replace(/&/g, '\\u0026')));
</script>

<template>
    <Head>
        <title>{{ seo.title }}</title>
        <meta head-key="description" name="description" :content="seo.description">
        <meta head-key="robots" name="robots" :content="seo.robots">
        <link head-key="canonical" rel="canonical" :href="seo.canonical">
        <meta head-key="og:title" property="og:title" :content="seo.ogTitle">
        <meta head-key="og:description" property="og:description" :content="seo.ogDescription">
        <meta head-key="og:url" property="og:url" :content="seo.canonical">
        <meta head-key="og:type" property="og:type" :content="seo.ogType">
        <meta v-if="seo.ogImage" head-key="og:image" property="og:image" :content="seo.ogImage">
        <meta head-key="twitter:card" name="twitter:card" :content="seo.twitterCard">
        <meta head-key="twitter:title" name="twitter:title" :content="seo.ogTitle">
        <meta head-key="twitter:description" name="twitter:description" :content="seo.ogDescription">
        <meta v-if="seo.ogImage" head-key="twitter:image" name="twitter:image" :content="seo.ogImage">
        <meta v-if="seo.verification" head-key="google-site-verification" name="google-site-verification" :content="seo.verification">
        <component :is="'script'" v-for="(item, index) in jsonLd" :key="index" :head-key="`schema-${index}`" type="application/ld+json">{{ item }}</component>
    </Head>
</template>
