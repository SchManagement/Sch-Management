<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import AdminIcon from '../Components/Admin/AdminIcon.vue';
import FlashMessages from '../Components/Admin/FlashMessages.vue';

const page = usePage();
const administrator = computed(() => page.props.auth?.user);
const mobileOpen = ref(false);
const sidebar = ref(null);
const mobileToggle = ref(null);
const collapsed = ref(false);
let previousBodyOverflow = '';
const groups = [
    { label: '', items: [{ label: 'Dashboard', href: '/admin', icon: 'dashboard', exact: true }] },
    { label: 'Website', items: [
        { label: 'Homepage', href: '/admin/homepage', icon: 'homepage' }, { label: 'Pages', href: '/admin/pages', icon: 'pages' },
        { label: 'Navigation', href: '/admin/navigation', icon: 'navigation', queryExclude: 'location=footer' },
        { label: 'Footer', href: '/admin/navigation?location=footer', icon: 'navigation', queryMatch: 'location=footer' },
    ]},
    { label: 'Content', items: [
        { label: 'Services', href: '/admin/services', icon: 'services' }, { label: 'Projects', href: '/admin/projects', icon: 'projects' },
        { label: 'Technologies', href: '/admin/technologies', icon: 'technology' }, { label: 'Articles', href: '/admin/articles', icon: 'articles' },
        { label: 'Categories', href: '/admin/categories', icon: 'categories' }, { label: 'Testimonials', href: '/admin/testimonials', icon: 'testimonials' },
    ]},
    { label: 'Communication', items: [{ label: 'Leads', href: '/admin/leads', icon: 'leads' }] },
    { label: 'Media', items: [{ label: 'Media Library', href: '/admin/media', icon: 'media' }] },
    { label: 'Company', items: [{ label: 'Company Information', href: '/admin/company', icon: 'company' }] },
    { label: 'System', items: [{ label: 'SEO', href: '/admin/seo', icon: 'seo' }, { label: 'Settings', href: '/admin/settings', icon: 'settings' }] },
];

const isActive = (item) => {
    const [path] = item.href.split('?');
    if (item.queryMatch) return page.url.startsWith(path) && page.url.includes(item.queryMatch);
    if (item.queryExclude && page.url.includes(item.queryExclude)) return false;
    return item.exact ? page.url === path : page.url.startsWith(path);
};
const setMobile = async (value, restoreFocus = true) => {
    if (value === mobileOpen.value) return;
    mobileOpen.value = value;
    document.body.style.overflow = value ? 'hidden' : previousBodyOverflow;
    await nextTick();
    if (value) sidebar.value?.querySelector('button[aria-label="Tutup navigasi"]')?.focus();
    else if (restoreFocus) mobileToggle.value?.focus();
};
const toggleCollapsed = () => { collapsed.value = !collapsed.value; localStorage.setItem('sch-admin-sidebar', collapsed.value ? 'collapsed' : 'expanded'); };
const onKey = (event) => {
    if (!mobileOpen.value) return;
    if (event.key === 'Escape') { event.preventDefault(); setMobile(false); return; }
    if (event.key !== 'Tab') return;
    const elements = [...(sidebar.value?.querySelectorAll('a[href], button:not([disabled])') || [])].filter(element => element.getClientRects().length);
    if (!elements.length) return;
    const first = elements[0]; const last = elements[elements.length - 1];
    if (event.shiftKey && (document.activeElement === first || !sidebar.value?.contains(document.activeElement))) { event.preventDefault(); last.focus(); }
    else if (!event.shiftKey && (document.activeElement === last || !sidebar.value?.contains(document.activeElement))) { event.preventDefault(); first.focus(); }
};
onMounted(() => { collapsed.value = localStorage.getItem('sch-admin-sidebar') === 'collapsed'; previousBodyOverflow = document.body.style.overflow; window.addEventListener('keydown', onKey); });
onBeforeUnmount(() => { document.body.style.overflow = previousBodyOverflow; window.removeEventListener('keydown', onKey); });
watch(() => page.url, () => setMobile(false, false));
</script>

<template>
    <div class="min-h-dvh w-full max-w-full bg-sch-surface text-sch-ink">
        <a href="#main-content" class="fixed left-4 top-4 z-[90] -translate-y-24 rounded-lg bg-white px-4 py-3 font-semibold text-sch-navy shadow-lg transition-transform focus:translate-y-0">Lewati ke konten utama</a>
        <FlashMessages />
        <div v-if="mobileOpen" class="fixed inset-0 z-40 bg-sch-midnight/55 lg:hidden" aria-hidden="true" @click="setMobile(false)" />
        <aside id="admin-sidebar" ref="sidebar" class="fixed inset-y-0 left-0 z-50 flex bg-sch-midnight text-white shadow-xl transition-[width,transform] duration-200 lg:visible lg:translate-x-0" :class="[mobileOpen ? 'translate-x-0' : 'invisible -translate-x-full', collapsed ? 'w-[18rem] lg:w-[5.5rem]' : 'w-[18rem]']" aria-label="Navigasi administrasi">
            <div class="flex min-w-0 flex-1 flex-col">
                <div class="flex min-h-20 items-center gap-3 border-b border-white/10 px-5" :class="collapsed ? 'lg:justify-center lg:px-3' : ''">
                    <Link href="/admin" class="flex min-w-0 flex-1 items-center gap-3 rounded-lg" aria-label="Dashboard SCH Management"><span class="grid size-10 shrink-0 place-items-center rounded-xl bg-sch-blue text-xs font-black tracking-[0.14em] shadow-[0_8px_24px_rgba(0,108,255,.28)]">SCH</span><span class="min-w-0" :class="collapsed ? 'lg:hidden' : ''"><span class="block truncate text-sm font-bold">SCH Management</span><span class="block truncate text-xs text-slate-400">Admin Console</span></span></Link>
                    <button type="button" class="grid size-11 shrink-0 place-items-center rounded-xl text-slate-300 hover:bg-white/10 hover:text-white lg:hidden" aria-label="Tutup navigasi" @click="setMobile(false)"><AdminIcon name="close" class="size-5"/></button>
                </div>
                <nav aria-label="Menu administrasi" class="min-h-0 flex-1 overflow-y-auto px-3 py-5">
                    <section v-for="group in groups" :key="group.label || 'main'" class="mb-5">
                        <h2 v-if="group.label" class="mb-2 px-3 text-[0.68rem] font-bold uppercase tracking-[0.16em] text-slate-500" :class="collapsed ? 'lg:hidden' : ''">{{ group.label }}</h2>
                        <div class="space-y-1"><Link v-for="item in group.items" :key="item.href" :href="item.href" class="flex min-h-11 items-center gap-3 rounded-xl px-3 text-sm font-medium transition-colors" :class="[isActive(item) ? 'bg-sch-blue text-white shadow-sm' : 'text-slate-300 hover:bg-white/8 hover:text-white', collapsed ? 'lg:justify-center' : '']" :title="collapsed ? item.label : undefined" :aria-current="isActive(item) ? 'page' : undefined"><AdminIcon :name="item.icon" class="size-5 shrink-0"/><span class="truncate" :class="collapsed ? 'lg:hidden' : ''">{{ item.label }}</span></Link></div>
                    </section>
                </nav>
                <div class="border-t border-white/10 p-3"><Link href="/admin/logout" method="post" as="button" type="button" class="flex min-h-11 w-full items-center gap-3 rounded-xl px-3 text-sm font-semibold text-slate-300 hover:bg-white/8 hover:text-white" :class="collapsed ? 'lg:justify-center' : ''" title="Keluar"><AdminIcon name="logout" class="size-5 shrink-0"/><span :class="collapsed ? 'lg:hidden' : ''">Keluar</span></Link></div>
            </div>
        </aside>
        <div class="min-w-0 w-full max-w-full transition-[padding] duration-200" :class="collapsed ? 'lg:pl-[5.5rem]' : 'lg:pl-[18rem]'">
            <header class="sticky top-0 z-30 w-full max-w-full border-b border-sch-border/90 bg-white/95 backdrop-blur">
                <div class="flex min-h-16 min-w-0 items-center justify-between gap-3 px-4 sm:gap-4 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 flex-1 items-center gap-2"><button ref="mobileToggle" type="button" class="grid size-11 shrink-0 place-items-center rounded-xl text-sch-navy hover:bg-slate-100 lg:hidden" :aria-expanded="mobileOpen" aria-controls="admin-sidebar" aria-label="Buka navigasi" @click="setMobile(true)"><AdminIcon name="menu" class="size-5"/></button><button type="button" class="hidden size-11 shrink-0 place-items-center rounded-xl text-sch-muted hover:bg-slate-100 hover:text-sch-navy lg:grid" :aria-label="collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'" @click="toggleCollapsed"><AdminIcon name="menu" class="size-5"/></button><p class="hidden min-w-0 truncate text-sm font-semibold text-sch-muted sm:block">Administrasi Website</p></div>
                    <div class="flex min-w-0 items-center gap-3"><div class="hidden min-w-0 text-right sm:block"><p class="truncate text-sm font-semibold text-sch-navy">{{ administrator?.name }}</p><p class="truncate text-xs text-sch-muted">Administrator</p></div><span class="grid size-10 shrink-0 place-items-center rounded-full bg-sch-soft-blue text-sm font-bold text-sch-blue" aria-hidden="true">{{ administrator?.name?.slice(0, 1)?.toUpperCase() }}</span></div>
                </div>
            </header>
            <main id="main-content" tabindex="-1" class="mx-auto min-w-0 w-full max-w-[100rem] px-4 py-6 sm:px-6 sm:py-8 lg:px-8"><slot /></main>
        </div>
    </div>
</template>
