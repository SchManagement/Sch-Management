<script setup>
import Breadcrumb from '../Components/Public/Breadcrumb.vue';
import PlainTextContent from '../Components/Public/PlainTextContent.vue';
import PublicLayout from '../Layouts/PublicLayout.vue';

defineProps({ contentPage: { type: Object, required: true } });
</script>

<template>
    <PublicLayout>
        <article>
            <header class="border-b border-sch-border bg-sch-surface py-14 sm:py-20">
                <div class="public-container">
                    <Breadcrumb :items="[{ label: 'Beranda', href: '/' }, { label: contentPage.title, current: true }]"/>
                    <p class="mt-10 text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">SCH Management</p>
                    <h1 class="public-heading mt-4 max-w-3xl text-sch-navy">{{ contentPage.title }}</h1>
                </div>
            </header>
            <div class="public-container py-12 sm:py-20">
                <PlainTextContent v-if="contentPage.content" :text="contentPage.content" class="max-w-[70ch]"/>
            </div>
        </article>
    </PublicLayout>
</template>
