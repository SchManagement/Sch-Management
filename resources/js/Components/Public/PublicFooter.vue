<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import BrandMark from './BrandMark.vue';
import SocialBrandIcon from './SocialBrandIcon.vue';
import SmartLink from './SmartLink.vue';
defineProps({ cookieSettingsAvailable: { type: Boolean, default: false } });
const emit = defineEmits(['open-cookie-settings']);

const page = usePage();
const site = computed(() => page.props.publicSite || {});
const brand = computed(() => site.value.brand || {
    name: 'SCH Management',
    tagline: 'WEB SOLUTIONS | IT SERVICES | DIGITAL GROWTH',
});
const contact = computed(() => site.value.contact || {});
const social = computed(() => site.value.social || {});
const fallbackGroups = [
    { label: 'Perusahaan', children: [{ label: 'Tentang', url: '/about' }, { label: 'Kontak', url: '/contact' }] },
    { label: 'Layanan', children: [{ label: 'Layanan', url: '/services' }, { label: 'Portfolio', url: '/portfolio' }, { label: 'Konsultasi Project', url: '/request-project' }] },
    { label: 'Sumber Daya', children: [{ label: 'Insight', url: '/insights' }] },
];
const groups = computed(() => {
    const cms = site.value.navigation?.footer || [];
    if (!cms.length) return fallbackGroups;
    const nested = cms.filter(item => item.children?.length);
    const flat = cms.filter(item => item.url && !item.children?.length);
    return [...nested, ...(flat.length ? [{ label: 'Navigasi', children: flat }] : [])];
});
const socialDefinitions = [
    { key: 'linkedin', label: 'LinkedIn SCH Management', iconClass: 'text-[#0A66C2]' },
    { key: 'instagram', label: 'Instagram SCH Management', iconClass: '' },
    { key: 'whatsapp', label: 'WhatsApp SCH Management', iconClass: 'text-[#25D366]' },
];
const socials = computed(() => socialDefinitions
    .map(item => ({ ...item, url: social.value[item.key] }))
    .filter(item => item.url));
const year = new Date().getFullYear();
</script>

<template>
    <footer class="bg-sch-midnight text-white">
        <div class="public-container py-12 sm:py-14 lg:py-16">
            <div class="grid gap-9 border-b border-white/15 pb-9 sm:gap-12 sm:pb-11 lg:grid-cols-[1.25fr_2fr] lg:gap-20">
                <div class="max-w-md">
                    <BrandMark :name="brand.name" logo="/images/brand/sch-logo-horizontal.png" horizontal light/>
                    <p v-if="brand.description" class="mt-4 text-base leading-7 text-slate-400">{{ brand.description }}</p>

                    <nav v-if="socials.length" class="mt-7 flex flex-wrap gap-2" aria-label="Media sosial SCH Management">
                        <a v-for="item in socials" :key="item.key" :href="item.url" target="_blank" rel="noopener noreferrer" class="group grid size-11 cursor-pointer place-items-center rounded-sch-md border border-white/15 bg-white/[0.04] transition-[transform,border-color,background-color] duration-200 hover:-translate-y-0.5 hover:border-white/30 hover:bg-white/[0.08] active:translate-y-0 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-cyan-300 motion-reduce:transform-none motion-reduce:transition-none" :aria-label="item.label" :title="item.label">
                            <SocialBrandIcon :name="item.key" class="size-[1.375rem] transition-[filter,transform] duration-200 group-hover:scale-105 group-hover:brightness-110 motion-reduce:transform-none motion-reduce:transition-none" :class="item.iconClass"/>
                        </a>
                    </nav>
                </div>

                <nav aria-label="Navigasi footer" class="grid grid-cols-2 gap-x-6 gap-y-6 sm:grid-cols-3 sm:gap-y-10">
                    <section v-for="group in groups" :key="group.label">
                        <h2 class="text-xs font-bold uppercase tracking-[0.14em] text-white">{{ group.label }}</h2>
                        <ul class="mt-4 space-y-1">
                            <li v-for="item in group.children" :key="item.label">
                                <SmartLink v-if="item.url" :href="item.url" :target="item.target" class="inline-flex min-h-11 items-center rounded-sch-sm text-sm text-slate-400 transition-colors hover:text-white">{{ item.label }}</SmartLink>
                            </li>
                        </ul>
                    </section>
                </nav>
            </div>

            <div class="flex flex-col gap-2 pt-6 text-sm text-slate-300 lg:flex-row lg:items-center lg:justify-between lg:gap-6">
                <p>&copy; {{ year }} {{ brand.name }}. Hak cipta dilindungi.</p>
                <a v-if="contact.email" :href="`mailto:${contact.email}`" class="inline-flex min-h-11 min-w-0 items-center rounded-sch-sm text-slate-300 transition-colors hover:text-white focus-visible:outline-white">
                    <span class="wrap-anywhere">{{ contact.email }}</span>
                </a>
                <div class="flex flex-wrap gap-x-6 gap-y-2">
                    <SmartLink href="/privacy-policy" class="inline-flex min-h-11 items-center py-2 transition-colors hover:text-white">Kebijakan Privasi</SmartLink>
                    <SmartLink href="/terms" class="inline-flex min-h-11 items-center py-2 transition-colors hover:text-white">Ketentuan</SmartLink>
                    <button v-if="cookieSettingsAvailable" type="button" class="inline-flex min-h-11 items-center py-2 text-left transition-colors hover:text-white" @click="emit('open-cookie-settings')">Pengaturan Cookie</button>
                </div>
            </div>
        </div>
    </footer>
</template>
