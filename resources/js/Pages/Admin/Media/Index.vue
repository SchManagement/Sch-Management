<script setup>
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, nextTick, onBeforeUnmount, ref } from 'vue';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import AdminPageHeader from '../../../Components/Admin/AdminPageHeader.vue';
import AdminCard from '../../../Components/Admin/AdminCard.vue';
import AdminButton from '../../../Components/Admin/AdminButton.vue';
import AdminIcon from '../../../Components/Admin/AdminIcon.vue';
import AdminPagination from '../../../Components/Admin/AdminPagination.vue';
import ConfirmDialog from '../../../Components/Admin/ConfirmDialog.vue';
import EmptyState from '../../../Components/Admin/EmptyState.vue';

const props = defineProps({ media: Object, filters: Object, categories: Array });
const page = usePage();
const filterForm = useForm({
    search: props.filters.search || '',
    category: props.filters.category || '',
    mime: props.filters.mime || '',
});
const uploadForm = useForm({ image: null, category: 'general', alt_text: '', caption: '' });
const uploadDialog = ref(null);
const uploadClose = ref(null);
const uploadFile = ref(null);
const uploadErrorSummary = ref(null);
const previewUrl = ref('');
const target = ref(null);
const deleting = ref(false);

const uploadErrors = computed(() => Object.values(uploadForm.errors));
const filter = () => filterForm.get(page.url.split('?')[0], { preserveState: true, replace: true });
const size = value => value >= 1048576
    ? `${(value / 1048576).toFixed(1)} MB`
    : `${Math.max(1, Math.round(value / 1024))} KB`;
const categoryLabel = value => String(value || 'general').replaceAll(/[-_]/g, ' ').replace(/\b\w/g, letter => letter.toUpperCase());

const clearPreview = () => {
    if (previewUrl.value) URL.revokeObjectURL(previewUrl.value);
    previewUrl.value = '';
};
const selectFile = (event) => {
    const file = event.target.files?.[0] || null;
    clearPreview();
    uploadForm.image = file;
    uploadForm.clearErrors('image');
    if (file?.type.startsWith('image/')) previewUrl.value = URL.createObjectURL(file);
};
const resetUpload = () => {
    clearPreview();
    uploadForm.reset();
    uploadForm.clearErrors();
    if (uploadFile.value) uploadFile.value.value = '';
};
const openUpload = async () => {
    if (!uploadDialog.value?.open) uploadDialog.value?.showModal();
    await nextTick();
    uploadClose.value?.focus();
};
const closeUpload = () => {
    if (!uploadForm.processing) uploadDialog.value?.close();
};
const cancelUpload = (event) => {
    if (uploadForm.processing) event.preventDefault();
};
const submitUpload = () => {
    uploadForm.post('/admin/media', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => uploadDialog.value?.close(),
        onError: () => nextTick(() => uploadErrorSummary.value?.focus()),
    });
};
const remove = () => {
    if (!target.value) return;
    deleting.value = true;
    router.delete(`/admin/media/${target.value.id}`, {
        preserveScroll: true,
        onSuccess: () => { target.value = null; },
        onFinish: () => { deleting.value = false; },
    });
};

onBeforeUnmount(clearPreview);
</script>

<template>
    <Head title="Media Library | Admin SCH Management">
        <meta name="robots" content="noindex, nofollow"/>
    </Head>
    <AdminLayout>
        <AdminPageHeader title="Media Library" description="Unggah dan kelola gambar publik yang digunakan pada website.">
            <template #action>
                <AdminButton icon="plus" class="shrink-0" @click="openUpload">Upload Media</AdminButton>
            </template>
        </AdminPageHeader>

        <AdminCard>
            <form class="grid gap-3 border-b border-sch-border p-4 sm:grid-cols-2 lg:grid-cols-[1fr_13rem_11rem_auto]" role="search" @submit.prevent="filter">
                <label class="text-xs font-semibold text-sch-muted">
                    Pencarian
                    <input v-model="filterForm.search" type="search" placeholder="Nama atau path file..." class="mt-1.5 min-h-11 w-full rounded-xl border border-sch-border px-3.5 text-sm focus:border-sch-blue focus:outline-none focus:ring-3 focus:ring-blue-100"/>
                </label>
                <label class="text-xs font-semibold text-sch-muted">
                    Kategori
                    <select v-model="filterForm.category" class="mt-1.5 min-h-11 w-full rounded-xl border border-sch-border px-3 text-sm focus:border-sch-blue focus:outline-none focus:ring-3 focus:ring-blue-100">
                        <option value="">Semua</option>
                        <option v-for="category in categories" :key="category" :value="category">{{ categoryLabel(category) }}</option>
                    </select>
                </label>
                <label class="text-xs font-semibold text-sch-muted">
                    Jenis
                    <select v-model="filterForm.mime" class="mt-1.5 min-h-11 w-full rounded-xl border border-sch-border px-3 text-sm focus:border-sch-blue focus:outline-none focus:ring-3 focus:ring-blue-100">
                        <option value="">Semua</option>
                        <option value="image/">Gambar</option>
                        <option value="application/">Dokumen</option>
                    </select>
                </label>
                <AdminButton type="submit" class="self-end" :disabled="filterForm.processing">{{ filterForm.processing ? 'Memuat...' : 'Terapkan' }}</AdminButton>
            </form>

            <EmptyState v-if="!media.data.length" title="Belum ada media." description="Unggah gambar pertama untuk mulai mengisi media library."/>
            <template v-else>
                <div class="grid gap-4 p-4 sm:grid-cols-2 xl:grid-cols-3">
                    <article v-for="item in media.data" :key="item.id" class="min-w-0 overflow-hidden rounded-xl border border-sch-border bg-white">
                        <div class="aspect-[16/10] bg-sch-subtle">
                            <img v-if="item.url" :src="item.url" :alt="item.alt_text || item.original_name" :width="item.width || undefined" :height="item.height || undefined" class="size-full object-cover" loading="lazy"/>
                            <div v-else class="grid size-full place-items-center text-sch-muted"><AdminIcon name="media" class="size-10"/></div>
                        </div>
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h2 class="truncate font-semibold text-sch-navy" :title="item.original_name">{{ item.original_name }}</h2>
                                    <p class="mt-1 truncate text-xs text-sch-muted" :title="item.path">{{ item.path }}</p>
                                </div>
                                <span class="rounded-lg bg-sch-soft-blue px-2 py-1 text-xs font-semibold uppercase text-sch-blue">{{ item.extension || 'file' }}</span>
                            </div>
                            <dl class="mt-4 grid grid-cols-2 gap-3 text-xs">
                                <div><dt class="text-sch-muted">Ukuran</dt><dd class="mt-1 font-semibold text-sch-ink">{{ size(item.size) }}</dd></div>
                                <div><dt class="text-sch-muted">Dimensi</dt><dd class="mt-1 font-semibold text-sch-ink">{{ item.width && item.height ? `${item.width} x ${item.height}` : '-' }}</dd></div>
                                <div><dt class="text-sch-muted">Kategori</dt><dd class="mt-1 font-semibold text-sch-ink">{{ categoryLabel(item.category) }}</dd></div>
                                <div><dt class="text-sch-muted">Uploader</dt><dd class="mt-1 truncate font-semibold text-sch-ink">{{ item.uploader?.name || '-' }}</dd></div>
                                <div class="col-span-2"><dt class="text-sch-muted">Alt text</dt><dd class="mt-1 break-words text-sch-ink">{{ item.alt_text || 'Belum diisi' }}</dd></div>
                            </dl>
                            <div class="mt-4 flex justify-end border-t border-sch-border pt-4">
                                <AdminButton variant="danger" icon="trash" @click="target = item">Hapus</AdminButton>
                            </div>
                        </div>
                    </article>
                </div>
                <AdminPagination :links="media.links" :from="media.from" :to="media.to" :total="media.total"/>
            </template>
        </AdminCard>

        <dialog ref="uploadDialog" class="m-auto w-[calc(100%-2rem)] max-w-2xl rounded-2xl bg-white p-0 text-sch-ink shadow-2xl backdrop:bg-sch-midnight/60" aria-labelledby="upload-media-title" @close="resetUpload" @cancel="cancelUpload">
            <form class="max-h-[calc(100dvh-2rem)] overflow-y-auto" @submit.prevent="submitUpload">
                <div class="flex items-start justify-between gap-5 border-b border-sch-border p-5 sm:p-6">
                    <div>
                        <h2 id="upload-media-title" class="text-xl font-bold text-sch-navy">Upload Media</h2>
                        <p class="mt-1 text-sm leading-6 text-sch-muted">Format JPG, JPEG, PNG, atau WebP. Maksimal 5 MB.</p>
                    </div>
                    <button ref="uploadClose" type="button" class="grid size-11 shrink-0 place-items-center rounded-xl text-sch-muted transition-colors hover:bg-slate-100 hover:text-sch-navy disabled:cursor-not-allowed disabled:opacity-50" aria-label="Tutup form upload" :disabled="uploadForm.processing" @click="closeUpload">
                        <AdminIcon name="close" class="size-5"/>
                    </button>
                </div>

                <div class="space-y-5 p-5 sm:p-6">
                    <div v-if="uploadForm.hasErrors" ref="uploadErrorSummary" tabindex="-1" role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 focus:outline-none focus:ring-3 focus:ring-red-100">
                        <p class="font-bold">Upload belum dapat diproses.</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5"><li v-for="error in uploadErrors" :key="error">{{ error }}</li></ul>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-[minmax(0,1.1fr)_minmax(12rem,.9fr)]">
                        <div>
                            <label for="media-image" class="mb-2 block text-sm font-semibold text-sch-navy">Gambar <span class="text-red-600" aria-hidden="true">*</span></label>
                            <label for="media-image" class="flex min-h-44 cursor-pointer flex-col items-center justify-center rounded-xl border border-dashed px-5 py-8 text-center transition-colors hover:border-sch-blue hover:bg-sch-soft-blue/40" :class="uploadForm.errors.image ? 'border-red-400 bg-red-50/40' : 'border-sch-border'">
                                <AdminIcon name="media" class="size-9 text-sch-blue"/>
                                <span class="mt-3 text-sm font-semibold text-sch-navy">Pilih gambar dari perangkat</span>
                                <span class="mt-1 text-xs leading-5 text-sch-muted">Nama file akan diganti otomatis dengan ID unik.</span>
                            </label>
                            <input id="media-image" ref="uploadFile" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="sr-only" :aria-invalid="uploadForm.errors.image ? 'true' : undefined" :aria-describedby="uploadForm.errors.image ? 'media-image-error' : 'media-image-help'" @change="selectFile"/>
                            <p id="media-image-help" class="mt-2 text-xs leading-5 text-sch-muted">File executable dan SVG tidak diterima.</p>
                            <p v-if="uploadForm.errors.image" id="media-image-error" class="mt-1 text-sm font-medium text-red-700">{{ uploadForm.errors.image }}</p>
                        </div>

                        <div class="overflow-hidden rounded-xl border border-sch-border bg-sch-subtle">
                            <img v-if="previewUrl" :src="previewUrl" alt="Preview gambar yang akan diunggah" class="aspect-square size-full object-contain"/>
                            <div v-else class="grid aspect-square place-items-center p-6 text-center text-sm text-sch-muted">Preview gambar akan tampil di sini.</div>
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <label class="text-sm font-semibold text-sch-navy">
                            Kategori
                            <select v-model="uploadForm.category" class="mt-2 min-h-11 w-full rounded-xl border bg-white px-3.5 text-sm font-normal text-sch-ink shadow-sm focus:border-sch-blue focus:outline-none focus:ring-3 focus:ring-blue-100" :class="uploadForm.errors.category ? 'border-red-400' : 'border-sch-border'" :aria-invalid="uploadForm.errors.category ? 'true' : undefined">
                                <option v-for="category in categories" :key="category" :value="category">{{ categoryLabel(category) }}</option>
                            </select>
                            <span v-if="uploadForm.errors.category" class="mt-1.5 block text-sm font-medium text-red-700">{{ uploadForm.errors.category }}</span>
                        </label>
                        <label class="text-sm font-semibold text-sch-navy">
                            Alt text
                            <input v-model="uploadForm.alt_text" type="text" maxlength="255" placeholder="Deskripsi singkat gambar" class="mt-2 min-h-11 w-full rounded-xl border bg-white px-3.5 text-sm font-normal text-sch-ink shadow-sm placeholder:text-slate-400 focus:border-sch-blue focus:outline-none focus:ring-3 focus:ring-blue-100" :class="uploadForm.errors.alt_text ? 'border-red-400' : 'border-sch-border'" :aria-invalid="uploadForm.errors.alt_text ? 'true' : undefined"/>
                            <span v-if="uploadForm.errors.alt_text" class="mt-1.5 block text-sm font-medium text-red-700">{{ uploadForm.errors.alt_text }}</span>
                        </label>
                    </div>

                    <label class="block text-sm font-semibold text-sch-navy">
                        Caption <span class="font-normal text-sch-muted">(opsional)</span>
                        <textarea v-model="uploadForm.caption" rows="3" maxlength="5000" placeholder="Catatan atau caption media" class="mt-2 w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm font-normal text-sch-ink shadow-sm placeholder:text-slate-400 focus:border-sch-blue focus:outline-none focus:ring-3 focus:ring-blue-100" :class="uploadForm.errors.caption ? 'border-red-400' : 'border-sch-border'" :aria-invalid="uploadForm.errors.caption ? 'true' : undefined"/>
                        <span v-if="uploadForm.errors.caption" class="mt-1.5 block text-sm font-medium text-red-700">{{ uploadForm.errors.caption }}</span>
                    </label>

                    <div v-if="uploadForm.processing" class="rounded-xl bg-sch-soft-blue p-4" role="status" aria-live="polite">
                        <div class="flex items-center justify-between gap-3 text-sm font-semibold text-sch-navy"><span>Mengunggah media...</span><span>{{ uploadForm.progress?.percentage || 0 }}%</span></div>
                        <progress class="mt-2 h-2 w-full accent-sch-blue" max="100" :value="uploadForm.progress?.percentage || 0"/>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-2 border-t border-sch-border p-5 sm:flex-row sm:justify-end sm:p-6">
                    <AdminButton variant="secondary" :disabled="uploadForm.processing" @click="closeUpload">Batal</AdminButton>
                    <AdminButton type="submit" icon="plus" :disabled="uploadForm.processing || !uploadForm.image">{{ uploadForm.processing ? 'Mengunggah...' : 'Upload Media' }}</AdminButton>
                </div>
            </form>
        </dialog>

        <ConfirmDialog :open="Boolean(target)" title="Hapus media?" :message="`File ${target?.original_name || 'ini'} dan record medianya akan dihapus. Tindakan ini tidak dapat dibatalkan.`" :busy="deleting" @close="target = null" @confirm="remove"/>
    </AdminLayout>
</template>
