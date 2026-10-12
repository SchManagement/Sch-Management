<script setup>
import Breadcrumb from '../../Components/Public/Breadcrumb.vue';
import InquiryForm from '../../Components/Public/InquiryForm.vue';
import PublicButton from '../../Components/Public/PublicButton.vue';
import PublicCTA from '../../Components/Public/PublicCTA.vue';
import PublicLayout from '../../Layouts/PublicLayout.vue';

defineProps({
    projectTypes: { type: Array, default: () => [] },
    budgetRanges: { type: Array, default: () => [] },
    recaptchaSiteKey: { type: String, default: '' },
});
</script>

<template>
    <PublicLayout header-mode="overlay">
        <section class="sch-internal-hero relative overflow-hidden bg-sch-navy py-12 text-white sm:py-20 lg:py-24" aria-labelledby="request-project-title">
            <div class="pointer-events-none absolute -right-28 top-0 size-96 rounded-full bg-sch-blue/15 blur-3xl" aria-hidden="true"/>
            <div class="public-container relative">
                <Breadcrumb dark :items="[{ label: 'Beranda', href: '/' }, { label: 'Konsultasi Project', current: true }]"/>
                <div class="sch-internal-hero-main mt-8 sm:mt-10">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-300">KONSULTASI PROJECT</p>
                        <h1 id="request-project-title" class="public-heading mt-4 max-w-4xl">Mari Rancang Langkah Digital Berikutnya.</h1>
                        <p class="public-body-lg mt-6 max-w-3xl text-slate-300">Bagikan tujuan, kebutuhan, dan perkiraan waktu project Anda. Empat langkah singkat ini membantu menyiapkan diskusi yang lebih terarah.</p>
                    </div>
                    <div class="sch-hero-art" aria-hidden="true"><img :src="'/images/brand/sch-icon.png'" alt="" width="1254" height="1254"/></div>
                </div>
            </div>
        </section>

        <section class="bg-sch-surface py-12 sm:py-20 lg:py-24" aria-label="Formulir konsultasi project">
            <div class="public-container grid gap-10 lg:grid-cols-[minmax(0,1fr)_20rem] lg:gap-12">
                <div class="sch-guided-form inquiry-panel min-w-0 self-start border border-sch-border bg-white p-5 shadow-sch-sm sm:p-8 lg:p-10" data-reveal>
                    <InquiryForm project :project-types="projectTypes" :budget-ranges="budgetRanges" :recaptcha-site-key="recaptchaSiteKey"/>
                </div>
                <aside class="sch-request-aside px-6 py-10 sm:px-8 lg:p-9" data-reveal>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-sch-blue">PENDEKATAN SCH</p>
                    <h2 class="public-subheading mt-4 text-sch-navy">Fokus pada kebutuhan, bukan formulir yang rumit.</h2>
                    <p class="mt-5 text-sm leading-7 text-sch-muted">Informasi ini digunakan untuk memahami konteks project dan menindaklanjuti permintaan Anda. Tidak ada harga atau hasil yang dijanjikan hanya dari isian awal.</p>
                    <div class="mt-8 border-t border-sch-border pt-6 text-sm leading-7 text-sch-muted">
                        <p class="font-semibold text-sch-navy">Belum yakin dengan ruang lingkupnya?</p>
                        <p class="mt-2">Tuliskan gambaran yang sudah Anda miliki. Detail dapat dibahas pada tahap berikutnya.</p>
                    </div>
                    <div class="sch-request-callout mt-9 p-6 text-white">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-cyan-300">PERLU DISKUSI DAHULU?</p>
                        <p class="mt-3 text-lg font-semibold leading-snug">Mulai dari pertanyaan yang Anda miliki.</p>
                        <PublicButton href="/contact" variant="inverted" arrow class="mt-5">Hubungi Kami</PublicButton>
                    </div>
                </aside>
            </div>
        </section>

        <section class="sch-request-sequence py-16 sm:py-20" aria-labelledby="request-process-title">
            <div class="public-container">
                <div class="grid gap-6 lg:grid-cols-[0.75fr_1fr] lg:items-end">
                    <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">SETELAH FORMULIR</p><h2 id="request-process-title" class="public-subheading mt-4 max-w-xl">Informasi yang jelas membantu memulai diskusi yang terarah.</h2></div>
                    <p class="max-w-2xl text-base leading-8 text-sch-muted">Informasi pada formulir menjadi konteks awal. Ruang lingkup, pendekatan, dan langkah berikutnya dapat dibahas bersama tim SCH Management.</p>
                </div>
                <ol class="mt-10 grid gap-6 md:grid-cols-3">
                    <li><span class="font-mono text-sm font-bold text-sch-blue">1 /</span><h3 class="mt-4 text-lg font-semibold text-sch-navy">Tinjau kebutuhan</h3><p class="mt-2 text-sm leading-7 text-sch-muted">Tujuan dan konteks project menjadi dasar percakapan.</p></li>
                    <li><span class="font-mono text-sm font-bold text-sch-blue">2 /</span><h3 class="mt-4 text-lg font-semibold text-sch-navy">Bahas ruang lingkup</h3><p class="mt-2 text-sm leading-7 text-sch-muted">Detail yang belum pasti dapat diperjelas dalam diskusi.</p></li>
                    <li><span class="font-mono text-sm font-bold text-sch-blue">3 /</span><h3 class="mt-4 text-lg font-semibold text-sch-navy">Susun langkah</h3><p class="mt-2 text-sm leading-7 text-sch-muted">Arah pengembangan ditentukan sesuai kebutuhan yang dipahami.</p></li>
                </ol>
            </div>
        </section>

        <section class="bg-white py-16 sm:py-20"><div class="public-container"><PublicCTA heading="Punya kebutuhan digital yang ingin dibahas?" description="Mulai dari konteks bisnis Anda untuk menentukan arah solusi digital yang relevan." :primary-action="{ label: 'Hubungi Kami', href: '/contact' }" :secondary-action="{ label: 'Lihat Layanan', href: '/services' }"/></div></section>
    </PublicLayout>
</template>

<style scoped>
@media (max-width: 374px) {
    .inquiry-panel { padding-inline: 0.5rem; }
}
</style>
