<script setup>
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import PublicButton from './PublicButton.vue';

const props = defineProps({ open: Boolean });
const emit = defineEmits(['update:open', 'analytics-change']);
const storageKey = 'sch-cookie-consent-v1';
const decided = ref(true);
const preferencesOpen = ref(false);
const analytics = ref(false);
const savedAnalytics = ref(false);
const dialog = ref(null);
const closeButton = ref(null);
let previousFocus = null;
let previousOverflow = '';

function save(value) {
    analytics.value = value;
    savedAnalytics.value = value;
    decided.value = true;
    try { localStorage.setItem(storageKey, JSON.stringify({ version: 1, essential: true, analytics: value })); } catch { /* Private storage may be unavailable. */ }
    emit('analytics-change', value);
    closePreferences();
}

function openPreferences() {
    previousFocus = document.activeElement;
    previousOverflow = document.body.style.overflow;
    document.body.style.overflow = 'hidden';
    preferencesOpen.value = true;
    nextTick(() => closeButton.value?.focus());
}

function closePreferences() {
    if (!preferencesOpen.value) return;
    preferencesOpen.value = false;
    analytics.value = savedAnalytics.value;
    document.body.style.overflow = previousOverflow;
    emit('update:open', false);
    nextTick(() => previousFocus?.focus?.());
}

function onKeydown(event) {
    if (event.key === 'Escape') { closePreferences(); return; }
    if (event.key !== 'Tab' || !dialog.value) return;
    const items = [...dialog.value.querySelectorAll('button:not(:disabled), input:not(:disabled), a[href]')];
    if (!items.length) return;
    if (event.shiftKey && document.activeElement === items[0]) { event.preventDefault(); items.at(-1).focus(); }
    else if (!event.shiftKey && document.activeElement === items.at(-1)) { event.preventDefault(); items[0].focus(); }
}

watch(() => props.open, value => { if (value && !preferencesOpen.value) openPreferences(); });
onMounted(() => {
    try {
        const stored = JSON.parse(localStorage.getItem(storageKey) || 'null');
        if (stored?.version === 1 && stored?.essential === true && typeof stored.analytics === 'boolean') {
            decided.value = true;
            analytics.value = stored.analytics;
            savedAnalytics.value = stored.analytics;
            emit('analytics-change', stored.analytics);
        } else decided.value = false;
    } catch { decided.value = false; }
    if (props.open) openPreferences();
});
onBeforeUnmount(() => { if (preferencesOpen.value) document.body.style.overflow = previousOverflow; });
</script>

<template>
    <div v-if="!decided" class="fixed inset-x-4 bottom-[max(1rem,env(safe-area-inset-bottom))] z-[70] mx-auto max-h-[min(80dvh,34rem)] max-w-5xl overflow-y-auto border border-sch-border bg-white p-5 text-sch-ink shadow-[0_20px_70px_rgb(6_16_31_/_0.2)] sm:p-7 md:left-auto md:right-6 md:mx-0 md:w-[min(30rem,calc(100vw-3rem))] lg:w-[min(42rem,calc(100vw-3rem))]" role="region" aria-label="Persetujuan cookie">
        <div class="grid gap-3 lg:gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">Privasi Anda</p>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-sch-ink sm:text-base sm:leading-7">Cookie esensial menjaga website tetap berfungsi. Dengan izin Anda, analitik membantu kami memahami penggunaannya. Anda dapat menolak tanpa mengurangi akses.</p>
            </div>
            <div class="cookie-actions grid grid-cols-2 gap-2 sm:flex sm:flex-wrap">
                <PublicButton type="button" @click="save(true)">Terima Semua</PublicButton>
                <PublicButton type="button" variant="outline" @click="save(false)">Tolak Non-Esensial</PublicButton>
                <button type="button" class="col-span-2 min-h-11 px-2 text-left text-sm font-semibold text-sch-blue underline underline-offset-4 sm:px-3" @click="openPreferences">Atur Preferensi</button>
            </div>
        </div>
    </div>

    <Teleport to="body">
        <div v-if="preferencesOpen" class="fixed inset-0 z-[100] grid place-items-center overflow-y-auto bg-sch-midnight/75 px-4 py-6" @keydown="onKeydown">
            <div ref="dialog" role="dialog" aria-modal="true" aria-labelledby="cookie-dialog-title" aria-describedby="cookie-dialog-description" class="w-full max-w-lg border border-sch-border bg-white p-6 text-sch-ink shadow-[0_25px_80px_rgb(0_0_0_/_0.28)] sm:p-8">
                <div class="flex items-start justify-between gap-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-sch-blue">SCH Management</p>
                        <h2 id="cookie-dialog-title" class="mt-2 text-2xl font-bold tracking-[-0.035em] text-sch-navy">Preferensi Cookie</h2>
                    </div>
                    <button ref="closeButton" type="button" class="grid size-11 shrink-0 place-items-center border border-sch-border text-sch-navy hover:bg-sch-surface" aria-label="Tutup preferensi cookie" @click="closePreferences">×</button>
                </div>
                <p id="cookie-dialog-description" class="mt-4 text-sm leading-7 text-sch-muted">Pilih apakah SCH Management boleh menggunakan analitik. Cookie esensial selalu aktif agar website dan formulir berfungsi.</p>
                <div class="mt-7 divide-y divide-sch-border border-y border-sch-border">
                    <div class="flex items-start justify-between gap-5 py-5">
                        <div><h3 class="font-semibold text-sch-navy">Esensial</h3><p class="mt-1 text-sm leading-6 text-sch-muted">Diperlukan untuk fungsi dan keamanan website.</p></div>
                        <span class="shrink-0 text-sm font-bold text-sch-navy">Selalu aktif</span>
                    </div>
                    <label class="flex cursor-pointer items-start justify-between gap-5 py-5">
                        <span><span class="block font-semibold text-sch-navy">Analitik</span><span class="mt-1 block text-sm leading-6 text-sch-muted">Membantu memahami penggunaan website melalui GA4.</span></span>
                        <input v-model="analytics" type="checkbox" class="mt-1 size-6 shrink-0 accent-sch-blue" aria-label="Izinkan cookie analitik">
                    </label>
                </div>
                <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <PublicButton type="button" variant="outline" @click="closePreferences">Batal</PublicButton>
                    <PublicButton type="button" @click="save(analytics)">Simpan Preferensi</PublicButton>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<style scoped>
@media (max-width: 639px) {
    .cookie-actions :deep(.public-button) { padding-inline: 0.5rem; font-size: 0.8125rem; }
}
</style>
