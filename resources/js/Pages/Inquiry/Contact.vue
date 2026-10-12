<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Breadcrumb from '../../Components/Public/Breadcrumb.vue';
import InquiryForm from '../../Components/Public/InquiryForm.vue';
import PublicButton from '../../Components/Public/PublicButton.vue';
import PublicIcon from '../../Components/Public/PublicIcon.vue';
import PublicLayout from '../../Layouts/PublicLayout.vue';

defineProps({
    projectTypes: { type: Array, default: () => [] },
    budgetRanges: { type: Array, default: () => [] },
    recaptchaSiteKey: { type: String, default: '' },
});

const page = usePage();
const email = computed(() => page.props.publicSite?.contact?.email || '');
const whatsapp = computed(() => page.props.publicSite?.contact?.whatsapp || '');
const whatsappDigits = computed(() => whatsapp.value.replace(/\D/g, ''));
</script>

<template>
    <PublicLayout header-mode="overlay">
        <section class="sch-internal-hero relative overflow-hidden bg-sch-navy py-12 text-white sm:py-20 lg:py-24" aria-labelledby="contact-title">
            <div class="pointer-events-none absolute -right-24 -top-24 size-96 rounded-full bg-sch-blue/15 blur-3xl" aria-hidden="true"/>
            <div class="public-container relative">
                <Breadcrumb dark :items="[{ label: 'Beranda', href: '/' }, { label: 'Kontak', current: true }]"/>
                <div class="sch-internal-hero-main mt-8 sm:mt-10">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-300">KONTAK SCH MANAGEMENT</p>
                        <h1 id="contact-title" class="public-heading mt-4 max-w-4xl">Mari Bicara Tentang Project Anda.</h1>
                        <p class="public-body-lg mt-6 max-w-3xl text-slate-300">Ceritakan kebutuhan digital Anda. SCH Management akan menindaklanjuti informasi yang dikirimkan untuk memahami kebutuhan project, ruang lingkup, dan langkah berikutnya.</p>
                    </div>
                    <div class="sch-hero-art" aria-hidden="true"><img :src="'/images/brand/sch-icon.png'" alt="" width="1254" height="1254"/></div>
                </div>
            </div>
        </section>

        <section class="bg-sch-surface py-12 sm:py-20 lg:py-24" aria-label="Informasi kontak dan formulir">
            <div class="public-container grid gap-12 lg:grid-cols-[minmax(0,0.72fr)_minmax(0,1.28fr)] lg:gap-16">
                <aside class="sch-consult-panel min-w-0 bg-sch-midnight px-6 py-10 text-white sm:px-8 lg:p-10" data-reveal>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-300">MULAI PERCAKAPAN</p>
                    <h2 class="public-subheading mt-4 max-w-sm text-white">Diskusi yang baik dimulai dari kebutuhan yang jelas.</h2>
                    <p class="mt-5 max-w-md text-base leading-8 text-slate-300">Gunakan formulir ini untuk menyampaikan gambaran awal. Belum perlu menyiapkan seluruh detail teknis.</p>

                    <div v-if="email || whatsapp" class="mt-10 space-y-6 border-t border-white/20 pt-7">
                        <div v-if="email">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-300">Email</p>
                            <a :href="'mailto:' + email" class="mt-2 inline-flex min-h-11 max-w-full items-center gap-3 text-base font-semibold text-white hover:text-cyan-300">
                                <PublicIcon name="mail" class="size-5 shrink-0" aria-hidden="true"/><span class="min-w-0 wrap-anywhere">{{ email }}</span>
                            </a>
                        </div>
                        <div v-if="whatsapp">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-300">WhatsApp</p>
                            <a v-if="whatsappDigits" :href="'https://wa.me/' + whatsappDigits" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex min-h-11 items-center gap-3 text-base font-semibold text-white hover:text-cyan-300">
                                <PublicIcon name="phone" class="size-5 shrink-0" aria-hidden="true"/>{{ whatsapp }} <span class="sr-only">(membuka situs eksternal)</span>
                            </a>
                            <p v-else class="mt-2 text-base text-white">{{ whatsapp }}</p>
                        </div>
                    </div>

                    <div class="mt-10 border-l-2 border-sch-blue pl-5">
                        <p class="text-sm font-semibold text-white">Punya kebutuhan yang lebih rinci?</p>
                        <p class="mt-2 text-sm leading-7 text-slate-300">Gunakan alur konsultasi bertahap untuk meninjau informasi sebelum dikirim.</p>
                        <PublicButton href="/request-project" variant="light" arrow class="mt-5">Konsultasi Project</PublicButton>
                    </div>
                </aside>

                <div class="inquiry-panel min-w-0 border border-sch-border bg-white p-5 shadow-sch-sm sm:p-8 lg:p-10" data-reveal>
                    <div class="mb-9 border-b border-sch-border pb-6">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">FORMULIR KONTAK</p>
                        <h2 class="mt-3 text-2xl font-semibold tracking-[-0.03em] text-sch-navy">Sampaikan kebutuhan Anda</h2>
                        <p class="mt-2 text-sm leading-7 text-sch-muted">Field bertanda * wajib diisi.</p>
                    </div>
                    <InquiryForm :project-types="projectTypes" :budget-ranges="budgetRanges" :recaptcha-site-key="recaptchaSiteKey"/>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<style scoped>
@media (max-width: 374px) {
    .inquiry-panel { padding-inline: 0.5rem; }
}
</style>
