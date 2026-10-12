<script setup>
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import FormError from './FormError.vue';

const props = defineProps({
    siteKey: { type: String, default: '' },
    modelValue: { type: String, default: '' },
    error: { type: String, default: '' },
});
const emit = defineEmits(['update:modelValue']);
const container = ref(null);
const loadError = ref(false);
let widgetId = null;
let disposed = false;

const renderWidget = async () => {
    await nextTick();
    if (disposed || !container.value || !window.grecaptcha?.render || widgetId !== null) return;
    widgetId = window.grecaptcha.render(container.value, {
        sitekey: props.siteKey,
        callback: token => emit('update:modelValue', token),
        'expired-callback': () => emit('update:modelValue', ''),
        'error-callback': () => {
            emit('update:modelValue', '');
            loadError.value = true;
        },
    });
};

const loadWidget = () => {
    if (!props.siteKey) return;
    if (window.grecaptcha?.render) {
        renderWidget();
        return;
    }
    if (!window.__schRecaptchaLoader) {
        window.__schRecaptchaLoader = new Promise((resolve, reject) => {
            window.__schRecaptchaReady = () => resolve();
            const script = document.createElement('script');
            script.src = 'https://www.google.com/recaptcha/api.js?onload=__schRecaptchaReady&render=explicit&hl=id';
            script.async = true;
            script.defer = true;
            script.onerror = () => reject(new Error('reCAPTCHA tidak dapat dimuat.'));
            document.head.appendChild(script);
        });
    }
    window.__schRecaptchaLoader.then(renderWidget).catch(() => { loadError.value = true; });
};

const reset = () => {
    emit('update:modelValue', '');
    if (widgetId !== null && window.grecaptcha?.reset) {
        window.grecaptcha.reset(widgetId);
    }
};

watch(() => props.siteKey, loadWidget);
onMounted(loadWidget);
onBeforeUnmount(() => {
    disposed = true;
    widgetId = null;
});
defineExpose({ reset });
</script>

<template>
    <div>
        <p id="recaptcha-label" class="mb-3 text-sm font-bold text-sch-navy">Verifikasi keamanan <span class="text-sch-danger" aria-hidden="true">*</span></p>
        <div id="recaptcha-widget" ref="container" class="min-h-[78px] w-full min-w-0" aria-labelledby="recaptcha-label" :aria-invalid="error ? 'true' : undefined"/>
        <FormError id="recaptcha-error" :message="error"/>
        <p v-if="loadError" class="mt-2 text-sm font-semibold text-sch-danger" role="alert">reCAPTCHA gagal dimuat. Periksa koneksi lalu muat ulang halaman.</p>
    </div>
</template>
