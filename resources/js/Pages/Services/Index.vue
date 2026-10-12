<script setup>
import Breadcrumb from '../../Components/Public/Breadcrumb.vue';
import PublicButton from '../../Components/Public/PublicButton.vue';
import PublicCTA from '../../Components/Public/PublicCTA.vue';
import ServiceCard from '../../Components/Public/ServiceCard.vue';
import PublicLayout from '../../Layouts/PublicLayout.vue';

defineProps({ services: { type: Array, default: () => [] } });
</script>

<template>
    <PublicLayout header-mode="overlay" style="--sch-section-space: clamp(3rem, 6vw, 5rem)">
        <section class="sch-internal-hero relative isolate overflow-hidden bg-sch-navy py-16 text-white sm:py-20 lg:py-24" aria-labelledby="services-title">
            <div class="pointer-events-none absolute right-0 top-0 -z-10 size-80 rounded-full bg-sch-blue/15 blur-3xl" aria-hidden="true"/>
            <div class="public-container relative">
                <Breadcrumb dark :items="[{ label: 'Beranda', href: '/' }, { label: 'Layanan', current: true }]"/>
                <div class="sch-internal-hero-main mt-10">
                <div><p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-300">LAYANAN</p>
                <h1 id="services-title" class="public-heading mt-4 max-w-4xl">Solusi Digital untuk Membantu Bisnis Berkembang.</h1>
                <p class="public-body-lg mt-6 max-w-3xl text-slate-300">
                    SCH Management menyediakan jasa pembuatan website profesional, company profile, landing page, web application, website maintenance, dan solusi IT yang disesuaikan dengan kebutuhan bisnis.
                </p></div>
                <div class="sch-hero-art" aria-hidden="true"><img :src="'/images/brand/sch-icon.png'" alt="" width="1254" height="1254"/></div>
                </div>
            </div>
        </section>

        <section class="public-section bg-sch-surface" aria-labelledby="services-grid-title">
            <div class="public-container">
                <div class="max-w-3xl">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">PILIHAN SOLUSI</p>
                    <h2 id="services-grid-title" class="public-subheading mt-4">Layanan yang Dibangun dengan Fondasi yang Terarah.</h2>
                </div>

                <div v-if="services.length" class="sch-capabilities mt-12 grid border-t border-sch-border sm:grid-cols-2 xl:grid-cols-3" data-stagger="service-index">
                    <ServiceCard v-for="(service, index) in services" :key="service.id" :service="service" :index="index" :tail="services.length % 3 === 0 && index === services.length - 1"/>
                </div>

                <div v-else class="mt-12 border-y border-sch-border py-10 sm:flex sm:items-center sm:justify-between sm:gap-8">
                    <div class="max-w-2xl">
                        <h3 class="text-xl font-bold text-sch-navy">Butuh solusi digital yang spesifik?</h3>
                        <p class="mt-3 text-sm leading-7 text-sch-muted">Ceritakan kebutuhan bisnis Anda untuk mendiskusikan pendekatan yang paling relevan.</p>
                    </div>
                    <PublicButton href="/request-project" arrow class="mt-6 sm:mt-0">Diskusikan Project</PublicButton>
                </div>
            </div>
        </section>

        <section class="sch-about-cta-section">
            <div class="public-container">
                <PublicCTA
                    class="sch-about-cta"
                    heading="Belum yakin layanan mana yang paling sesuai?"
                    description="Mulai dari kebutuhan dan tujuan bisnis Anda. Kami membantu menyusun arah solusi digital yang tepat."
                    :primary-action="{ label: 'Diskusikan Project', href: '/request-project' }"
                    :secondary-action="{ label: 'Tentang SCH', href: '/about' }"
                />
            </div>
        </section>
    </PublicLayout>
</template>
