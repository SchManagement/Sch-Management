<script setup>
import { computed } from 'vue';
import Breadcrumb from '../Components/Public/Breadcrumb.vue';
import PlainTextContent from '../Components/Public/PlainTextContent.vue';
import PublicButton from '../Components/Public/PublicButton.vue';
import PublicCTA from '../Components/Public/PublicCTA.vue';
import PublicIcon from '../Components/Public/PublicIcon.vue';
import ResponsiveImage from '../Components/Public/ResponsiveImage.vue';
import SectionHeading from '../Components/Public/SectionHeading.vue';
import PublicLayout from '../Layouts/PublicLayout.vue';

const props = defineProps({
    aboutPage: Object,
    founder: Object,
});

const pageTitle = computed(() => props.aboutPage?.title || 'Membangun Solusi Digital dengan Pendekatan yang Terarah.');
const story = computed(() => props.aboutPage?.content || `SCH Management adalah bisnis teknologi yang berfokus membantu bisnis membangun website, sistem digital, dan solusi IT yang selaras dengan kebutuhannya.

Kami memulai dari pemahaman terhadap konteks bisnis dan pengguna. Dari sana, teknologi, struktur, serta pengalaman digital dirancang agar efektif digunakan hari ini dan tetap mudah dikembangkan untuk kebutuhan berikutnya.`);

const missions = [
    'Memahami kebutuhan bisnis sebelum menentukan arah solusi dan teknologi.',
    'Membangun pengalaman digital yang jelas, responsif, dan relevan bagi pengguna.',
    'Menjaga fondasi pengembangan tetap terstruktur, mudah dipelihara, dan siap berkembang.',
];

const values = [
    { title: 'Strategic Thinking', description: 'Melihat teknologi dalam konteks tujuan, proses, dan kebutuhan bisnis.' },
    { title: 'Quality', description: 'Menjaga perhatian pada struktur, fungsi, detail, dan pengalaman pengguna.' },
    { title: 'Clarity', description: 'Mengutamakan komunikasi serta keputusan yang jelas sepanjang pengembangan.' },
    { title: 'Adaptability', description: 'Membangun solusi yang dapat menyesuaikan diri dengan kebutuhan yang berubah.' },
    { title: 'Long-Term Partnership', description: 'Memandang produk digital sebagai fondasi yang perlu dirawat dan dikembangkan.' },
];

const philosophy = [
    { title: 'Understand', description: 'Memahami konteks, kebutuhan, pengguna, dan tujuan.' },
    { title: 'Plan', description: 'Menyusun ruang lingkup serta prioritas secara terarah.' },
    { title: 'Design', description: 'Membentuk alur dan antarmuka yang jelas digunakan.' },
    { title: 'Develop', description: 'Membangun dengan struktur teknologi yang dapat dipelihara.' },
    { title: 'Validate', description: 'Memeriksa fungsi, kualitas, dan pengalaman secara menyeluruh.' },
    { title: 'Improve', description: 'Mengembangkan solusi mengikuti kebutuhan berikutnya.' },
];
</script>

<template>
    <PublicLayout header-mode="overlay" style="--sch-section-space: clamp(3rem, 6vw, 5rem)">
        <section class="sch-internal-hero relative isolate overflow-hidden bg-sch-navy py-16 text-white sm:py-20 lg:py-24" aria-labelledby="about-title">
            <div class="pointer-events-none absolute -right-32 top-0 -z-10 size-[34rem] rounded-full bg-sch-blue/15 blur-3xl" aria-hidden="true"/>
            <div class="public-container">
                <Breadcrumb dark :items="[{ label: 'Beranda', href: '/' }, { label: 'Tentang', current: true }]"/>
                <div class="sch-internal-hero-main mt-10">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-300">TENTANG SCH MANAGEMENT</p>
                        <h1 id="about-title" class="public-heading mt-5 max-w-4xl">{{ pageTitle }}</h1>
                        <p class="public-body-lg mt-6 max-w-3xl text-slate-300">
                            SCH Management membangun website, sistem digital, dan solusi IT dengan mempertimbangkan kebutuhan bisnis, pengalaman pengguna, serta kemudahan pengembangan jangka panjang.
                        </p>
                    </div>
                    <div class="sch-hero-art" aria-hidden="true"><img :src="'/images/brand/sch-icon.png'" alt="" width="1254" height="1254"/></div>
                </div>
            </div>
        </section>

        <section class="public-section bg-white" aria-labelledby="about-story-title">
            <div class="sch-about-story public-container grid gap-10 lg:grid-cols-[0.72fr_1.28fr] lg:gap-20">
                <div>
                    <SectionHeading
                        heading-id="about-story-title"
                        eyebrow="PENDEKATAN KAMI"
                        title="Teknologi Dimulai dari Pemahaman yang Tepat."
                    />
                    <div class="sch-story-art sch-about-story-art mt-10" aria-hidden="true"/>
                </div>
                <PlainTextContent :text="story" lead/>
            </div>
        </section>

        <section class="sch-dark-depth public-section bg-sch-midnight text-white" aria-labelledby="about-vision-title">
            <div class="public-container grid gap-12 lg:grid-cols-2 lg:gap-20">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-cyan-300">VISI</p>
                    <h2 id="about-vision-title" class="public-heading mt-5 max-w-xl">
                        Menjadi partner teknologi yang membantu bisnis bergerak maju melalui solusi digital yang relevan dan berkelanjutan.
                    </h2>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-cyan-300">MISI</p>
                    <h2 class="public-subheading mt-4">Menjaga Solusi Tetap Relevan dan Dapat Dikembangkan.</h2>
                    <ul class="mt-6 border-t border-white/20">
                        <li v-for="(mission, index) in missions" :key="mission" class="flex gap-4 border-b border-white/20 py-5">
                            <span class="font-mono text-sm font-bold text-cyan-300" aria-hidden="true">{{ index + 1 }}</span>
                            <p class="text-sm leading-7 text-slate-300">{{ mission }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="public-section bg-white" aria-labelledby="about-values-title">
            <div class="public-container">
                <SectionHeading
                    heading-id="about-values-title"
                    eyebrow="NILAI KERJA"
                    title="Prinsip yang Menjaga Setiap Keputusan Tetap Terarah."
                    description="Nilai ini menjadi cara SCH Management mempertimbangkan kualitas solusi, komunikasi, dan keberlanjutan pengembangan."
                />
                <div class="sch-values mt-14 border-y border-sch-border" data-stagger="about-values">
                    <article v-for="(value, index) in values" :key="value.title" class="grid gap-3 border-b border-sch-border py-6 last:border-0 sm:grid-cols-[4rem_0.7fr_1fr] sm:items-baseline sm:gap-8 sm:py-8">
                        <span class="font-mono text-xs font-bold text-sch-blue" aria-hidden="true">{{ index + 1 }}</span>
                        <h3 class="text-xl font-semibold tracking-tight text-sch-navy">{{ value.title }}</h3>
                        <p class="text-sm leading-7 text-sch-muted">{{ value.description }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section v-if="founder" class="public-section bg-sch-surface" aria-labelledby="about-founder-title">
            <div class="public-container grid gap-10 lg:grid-cols-[0.75fr_1.25fr] lg:items-center lg:gap-16">
                <div class="aspect-[4/3] overflow-hidden rounded-sch-lg bg-white">
                    <ResponsiveImage src="/images/about/about-founder.webp" :alt="`Foto ${founder.name}`" width="1448" height="1086"/>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">FOUNDER</p>
                    <h2 id="about-founder-title" class="public-subheading mt-4">{{ founder.name }}</h2>
                    <p v-if="founder.position" class="mt-2 font-semibold text-sch-blue">{{ founder.position }}</p>
                    <PlainTextContent v-if="founder.biography" :text="founder.biography" class="mt-6 max-w-3xl"/>
                    <div v-if="founder.email || founder.linkedin" class="mt-7 flex flex-wrap gap-3">
                        <PublicButton v-if="founder.email" :href="`mailto:${founder.email}`" variant="outline">Email</PublicButton>
                        <PublicButton v-if="founder.linkedin" :href="founder.linkedin" target="_blank" variant="outline">
                            LinkedIn
                            <PublicIcon name="arrow-up-right" class="size-4"/>
                            <span class="sr-only">(dibuka di tab baru)</span>
                        </PublicButton>
                    </div>
                </div>
            </div>
        </section>

        <section class="sch-dark-depth public-section bg-sch-dark-blue text-white" aria-labelledby="about-philosophy-title">
            <div class="public-container">
                <SectionHeading
                    heading-id="about-philosophy-title"
                    dark
                    eyebrow="DEVELOPMENT PHILOSOPHY"
                    title="Berpikir Terarah, Membangun dengan Jelas."
                    description="Pendekatan pengembangan yang menjaga kebutuhan bisnis, keputusan desain, dan implementasi teknologi tetap saling terhubung."
                />
                <ol class="mt-14 grid border-t border-white/20 sm:grid-cols-2 lg:grid-cols-6">
                    <li v-for="(item, index) in philosophy" :key="item.title" class="border-b border-white/20 py-7 sm:pr-5 lg:border-b-0">
                        <span class="font-mono text-xs font-bold text-cyan-300" aria-hidden="true">{{ index + 1 }}</span>
                        <h3 class="mt-4 text-lg font-bold text-white">{{ item.title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-300">{{ item.description }}</p>
                    </li>
                </ol>
            </div>
        </section>

        <section class="sch-about-cta-section">
            <div class="public-container">
                <PublicCTA
                    class="sch-about-cta"
                    heading="Punya kebutuhan digital yang ingin dikembangkan?"
                    description="Diskusikan kebutuhan bisnis Anda dan lihat layanan yang dapat menjadi langkah awal pengembangannya."
                    :primary-action="{ label: 'Diskusikan Project', href: '/request-project' }"
                    :secondary-action="{ label: 'Lihat Layanan', href: '/services' }"
                />
            </div>
        </section>
    </PublicLayout>
</template>
