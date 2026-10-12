<script setup>
import { nextTick, ref, watch } from 'vue';
import AdminButton from './AdminButton.vue';

const props = defineProps({ open: Boolean, title: String, message: String, confirmLabel: { type: String, default: 'Hapus' }, busy: Boolean });
const emit = defineEmits(['close', 'confirm']);
const dialog = ref(null);
watch(() => props.open, async (value) => {
    await nextTick();
    if (value && dialog.value && !dialog.value.open) dialog.value.showModal();
    if (!value && dialog.value?.open) dialog.value.close();
});
const onClose = () => emit('close');
</script>
<template>
    <dialog ref="dialog" class="m-auto w-[calc(100%-2rem)] max-w-md rounded-2xl bg-white p-0 text-sch-ink shadow-2xl backdrop:bg-sch-midnight/60" @close="onClose" @cancel.prevent="emit('close')">
        <div class="p-6"><div class="grid size-11 place-items-center rounded-xl bg-red-50 text-red-700" aria-hidden="true">!</div><h2 class="mt-4 text-lg font-bold text-sch-navy">{{ title }}</h2><p class="mt-2 text-sm leading-6 text-sch-muted">{{ message }}</p><div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end"><AdminButton variant="secondary" :disabled="busy" @click="emit('close')">Batal</AdminButton><AdminButton variant="danger" :disabled="busy" @click="emit('confirm')">{{ busy ? 'Memproses...' : confirmLabel }}</AdminButton></div></div>
    </dialog>
</template>
