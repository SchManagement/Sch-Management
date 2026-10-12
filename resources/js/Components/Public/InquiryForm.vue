<script setup>
import { computed, nextTick, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import PublicButton from './PublicButton.vue';
import PublicCheckbox from './PublicCheckbox.vue';
import PublicInput from './PublicInput.vue';
import PublicSelect from './PublicSelect.vue';
import PublicTextarea from './PublicTextarea.vue';
import RecaptchaCheckbox from './RecaptchaCheckbox.vue';

const props = defineProps({
    project: Boolean,
    projectTypes: { type: Array, default: () => [] },
    budgetRanges: { type: Array, default: () => [] },
    recaptchaSiteKey: { type: String, default: '' },
});

const steps = ['Informasi Anda', 'Project', 'Budget & Timeline', 'Review'];
const step = ref(1);
const submitted = ref(false);
const localErrors = ref({});
const errorSummary = ref(null);
const successHeading = ref(null);
const captcha = ref(null);
const stepContent = ref(null);
let submissionAnswered = false;
const form = useForm({
    name: '',
    company: '',
    email: '',
    whatsapp: '',
    project_type: '',
    budget_range: '',
    target_timeline: '',
    message: '',
    consent: false,
    'g-recaptcha-response': '',
    website: '',
});

const errors = computed(() => ({ ...form.errors, ...localErrors.value }));
const errorEntries = computed(() => Object.entries(errors.value).filter(([, message]) => Boolean(message)));
const fieldError = name => errors.value[name] || '';
const fieldId = name => name === 'g-recaptcha-response' ? 'recaptcha-widget' : name === 'form' ? null : 'inquiry-' + name;
const fieldLabel = name => ({
    name: 'Nama Lengkap',
    company: 'Nama Perusahaan',
    email: 'Email',
    whatsapp: 'Nomor WhatsApp',
    project_type: 'Jenis Project',
    budget_range: 'Estimasi Budget',
    target_timeline: 'Target Waktu',
    message: 'Deskripsi Kebutuhan',
    consent: 'Persetujuan',
    'g-recaptcha-response': 'reCAPTCHA',
    form: 'Pengiriman',
}[name] || 'Form');

const focusSummary = async () => {
    await nextTick();
    errorSummary.value?.focus({ preventScroll: false });
};

const validateStep = current => {
    const found = {};
    if (current === 1) {
        if (form.name.trim().length < 2) found.name = 'Masukkan nama lengkap Anda.';
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email.trim())) found.email = 'Masukkan alamat email yang valid.';
        if (!/^\+?[0-9][0-9\s().-]{6,30}$/.test(form.whatsapp.trim())) found.whatsapp = 'Masukkan nomor WhatsApp yang valid.';
    }
    if (current === 2) {
        if (!form.project_type) found.project_type = 'Pilih jenis project.';
        if (form.message.trim().length < 15) found.message = 'Jelaskan kebutuhan Anda dalam sedikitnya 15 karakter.';
    }
    if (current === 4) {
        if (!form.consent) found.consent = 'Persetujuan diperlukan untuk mengirim permintaan.';
        if (!form['g-recaptcha-response']) found['g-recaptcha-response'] = 'Verifikasi reCAPTCHA gagal. Silakan coba kembali.';
    }
    return found;
};

const nextStep = () => {
    localErrors.value = validateStep(step.value);
    if (Object.keys(localErrors.value).length) {
        focusSummary();
        return;
    }
    form.clearErrors();
    step.value = Math.min(4, step.value + 1);
    focusStep();
};

const focusStep = async () => {
    await nextTick();
    stepContent.value?.focus({ preventScroll: false });
};

const goToStep = number => {
    if (step.value === 4 && number < 4) {
        captcha.value?.reset();
        form['g-recaptcha-response'] = '';
    }
    step.value = number;
    localErrors.value = {};
    form.clearErrors();
    focusStep();
};

const previousStep = () => {
    if (step.value === 4) {
        captcha.value?.reset();
        form['g-recaptcha-response'] = '';
    }
    localErrors.value = {};
    form.clearErrors();
    step.value = Math.max(1, step.value - 1);
    focusStep();
};

const submit = () => {
    if (form.processing) return;
    if (props.project && step.value < 4) {
        nextStep();
        return;
    }
    localErrors.value = {};
    const checks = props.project ? [4] : [1, 2, 4];
    for (const current of checks) Object.assign(localErrors.value, validateStep(current));
    if (Object.keys(localErrors.value).length) {
        if (props.project) {
            const first = Object.keys(localErrors.value)[0];
            step.value = first === 'consent' || first === 'g-recaptcha-response' ? 4 : ['name', 'company', 'email', 'whatsapp'].includes(first) ? 1 : 2;
        }
        focusSummary();
        return;
    }

    submissionAnswered = false;
    form.post(props.project ? '/request-project' : '/contact', {
        preserveScroll: true,
        onSuccess: () => {
            submissionAnswered = true;
            submitted.value = true;
            form.reset();
            nextTick(() => successHeading.value?.focus({ preventScroll: false }));
        },
        onError: serverErrors => {
            submissionAnswered = true;
            captcha.value?.reset();
            form['g-recaptcha-response'] = '';
            if (props.project) {
                const first = Object.keys(serverErrors)[0];
                step.value = ['name', 'company', 'email', 'whatsapp'].includes(first) ? 1
                    : ['project_type', 'message'].includes(first) ? 2
                        : ['budget_range', 'target_timeline'].includes(first) ? 3 : 4;
            }
            focusSummary();
        },
        onFinish: () => {
            if (!submissionAnswered) {
                captcha.value?.reset();
                form['g-recaptcha-response'] = '';
                localErrors.value = { form: 'Permintaan belum dapat dikirim. Periksa koneksi Anda, lalu coba kembali.' };
                focusSummary();
            }
        },
    });
};
</script>

<template>
    <section v-if="submitted" class="border-l-4 border-sch-blue bg-sch-soft-blue px-6 py-10 sm:px-10" role="status" aria-live="polite">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-sch-navy">PERMINTAAN DITERIMA</p>
        <h2 ref="successHeading" tabindex="-1" class="public-subheading mt-4 text-sch-navy focus:outline-none">Permintaan Anda telah berhasil dikirim.</h2>
        <p class="mt-4 max-w-2xl text-base leading-8 text-sch-ink">Terima kasih telah menghubungi SCH Management. Informasi Anda telah diterima dan akan digunakan untuk menindaklanjuti kebutuhan project.</p>
        <div class="mt-8 flex flex-wrap gap-3">
            <PublicButton href="/" arrow>Kembali ke Beranda</PublicButton>
            <PublicButton href="/services" variant="outline">Lihat Layanan</PublicButton>
        </div>
    </section>

    <form v-else class="relative" novalidate @submit.prevent="submit">
        <div class="sr-only" aria-hidden="true">
            <label for="inquiry-website">Biarkan kolom ini kosong</label>
            <input id="inquiry-website" v-model="form.website" name="website" type="text" tabindex="-1" autocomplete="off" class="w-px">
        </div>

        <nav v-if="project" aria-label="Tahapan konsultasi" class="mb-7 sm:mb-10">
            <ol class="grid grid-cols-4 gap-2 border-b border-sch-border pb-4 sm:gap-4 sm:pb-5">
                <li v-for="(label, index) in steps" :key="label" class="min-w-0">
                    <button
                        type="button"
                        class="flex min-h-11 w-full items-center gap-2 text-left text-xs font-semibold sm:text-sm"
                        :class="index + 1 <= step ? 'text-sch-navy' : 'text-sch-muted'"
                        :disabled="index + 1 > step || form.processing"
                        :aria-current="index + 1 === step ? 'step' : undefined"
                        @click="goToStep(index + 1)"
                    >
                        <span class="grid size-8 shrink-0 place-items-center border font-mono text-xs" :class="index + 1 === step ? 'border-sch-blue bg-sch-blue text-white' : index + 1 < step ? 'border-sch-blue text-sch-blue' : 'border-sch-border text-sch-muted'">{{ index + 1 }}</span>
                        <span class="hidden min-w-0 sm:block">{{ label }}</span>
                    </button>
                </li>
            </ol>
            <p class="text-sm font-semibold text-sch-muted">Langkah {{ step }} dari 4 · {{ steps[step - 1] }}</p>
        </nav>

        <div v-if="errorEntries.length" ref="errorSummary" tabindex="-1" class="mb-8 scroll-mt-28 border-l-4 border-sch-danger bg-red-50 p-5 focus:outline-none" role="alert">
            <p class="font-bold text-sch-danger">Periksa kembali informasi berikut:</p>
            <ul class="mt-3 list-disc space-y-1 pl-5 text-sm text-sch-danger">
                <li v-for="([name, message]) in errorEntries" :key="name">
                    <a v-if="fieldId(name)" :href="'#' + fieldId(name)" class="underline underline-offset-2">{{ fieldLabel(name) }}: {{ message }}</a>
                    <span v-else>{{ message }}</span>
                </li>
            </ul>
        </div>

        <div :key="project ? step : 'contact'" ref="stepContent" class="inquiry-step-content min-w-0 focus:outline-none" tabindex="-1">
        <fieldset v-if="!project || step === 1" class="min-w-0">
            <legend class="public-subheading mb-3 text-sch-navy">{{ project ? '1 / Informasi Anda' : 'Informasi Anda' }}</legend>
            <p class="mb-7 max-w-2xl text-sm leading-7 text-sch-muted">Gunakan kontak yang dapat dihubungi untuk membahas kebutuhan Anda.</p>
            <div class="grid gap-6 sm:grid-cols-2">
                <PublicInput id="inquiry-name" v-model="form.name" label="Nama Lengkap" name="name" autocomplete="name" required :error="fieldError('name')"/>
                <PublicInput id="inquiry-company" v-model="form.company" label="Nama Perusahaan / Bisnis" name="company" autocomplete="organization" :error="fieldError('company')"/>
                <PublicInput id="inquiry-email" v-model="form.email" label="Email" name="email" type="email" autocomplete="email" required :error="fieldError('email')"/>
                <PublicInput id="inquiry-whatsapp" v-model="form.whatsapp" label="Nomor WhatsApp" name="whatsapp" type="tel" autocomplete="tel" required :error="fieldError('whatsapp')" help="Boleh menggunakan awalan +62 atau format nomor internasional."/>
            </div>
        </fieldset>

        <fieldset v-if="!project || step === 2" class="min-w-0" :class="project ? '' : 'mt-12 border-t border-sch-border pt-10'">
            <legend class="public-subheading mb-3 text-sch-navy">{{ project ? '2 / Project' : 'Kebutuhan Project' }}</legend>
            <p class="mb-7 max-w-2xl text-sm leading-7 text-sch-muted">Ceritakan apa yang ingin dibangun. Tidak perlu menyiapkan spesifikasi teknis lengkap.</p>
            <div class="space-y-6">
                <PublicSelect id="inquiry-project_type" v-model="form.project_type" label="Jenis Project" name="project_type" :options="projectTypes" required :error="fieldError('project_type')"/>
                <PublicTextarea id="inquiry-message" v-model="form.message" label="Pesan / Deskripsi Kebutuhan" name="message" :rows="project ? 8 : 6" :maxlength="10000" required :error="fieldError('message')" help="Jelaskan tujuan, fungsi yang dibutuhkan, atau tantangan yang sedang dihadapi."/>
            </div>
        </fieldset>

        <fieldset v-if="!project || step === 3" class="min-w-0" :class="project ? '' : 'mt-12 border-t border-sch-border pt-10'">
            <legend class="public-subheading mb-3 text-sch-navy">{{ project ? '3 / Budget & Timeline' : 'Perkiraan Pelaksanaan' }}</legend>
            <p class="mb-7 max-w-2xl text-sm leading-7 text-sch-muted">Perkiraan ini membantu menyesuaikan ruang lingkup diskusi, bukan penawaran harga tetap.</p>
            <div class="grid gap-6 sm:grid-cols-2">
                <PublicSelect id="inquiry-budget_range" v-model="form.budget_range" label="Estimasi Budget" name="budget_range" :options="budgetRanges" :error="fieldError('budget_range')"/>
                <PublicInput id="inquiry-target_timeline" v-model="form.target_timeline" label="Target Waktu" name="target_timeline" :error="fieldError('target_timeline')" placeholder="Contoh: Kuartal berikutnya"/>
            </div>
        </fieldset>

        <div v-if="project && step === 4">
            <h2 class="public-subheading text-sch-navy">4 / Tinjau Permintaan</h2>
            <p class="mt-3 text-sm leading-7 text-sch-muted">Pastikan informasi berikut sudah sesuai. Anda dapat kembali ke langkah sebelumnya untuk memperbaikinya.</p>
            <dl class="mt-7 divide-y divide-sch-border border-y border-sch-border text-sm">
                <div v-for="([label, value]) in [
                    ['Nama', form.name], ['Perusahaan', form.company], ['Email', form.email],
                    ['WhatsApp', form.whatsapp], ['Jenis Project', form.project_type],
                    ['Budget', form.budget_range], ['Target Waktu', form.target_timeline],
                ]" :key="label" class="grid gap-1 py-3 sm:grid-cols-[10rem_1fr] sm:gap-5">
                    <dt class="font-semibold text-sch-muted">{{ label }}</dt>
                    <dd class="break-words font-medium text-sch-ink">{{ value || 'Tidak diisi' }}</dd>
                </div>
                <div class="py-4">
                    <dt class="font-semibold text-sch-muted">Kebutuhan</dt>
                    <dd class="mt-2 whitespace-pre-wrap break-words leading-7 text-sch-ink">{{ form.message }}</dd>
                </div>
            </dl>
        </div>
        </div>

        <div v-if="!project || step === 4" class="mt-10 space-y-6 border-t border-sch-border pt-8">
            <PublicCheckbox id="inquiry-consent" v-model="form.consent" name="consent" required label="Saya menyetujui bahwa informasi ini digunakan untuk menindaklanjuti permintaan saya." :error="fieldError('consent')"/>
            <RecaptchaCheckbox v-if="recaptchaSiteKey" ref="captcha" v-model="form['g-recaptcha-response']" :site-key="recaptchaSiteKey" :error="fieldError('g-recaptcha-response')"/>
            <div v-else class="border-l-4 border-amber-500 bg-amber-50 p-4 text-sm leading-6 text-amber-900" role="status">Form belum dapat menerima permintaan karena verifikasi keamanan belum dikonfigurasi. Silakan coba lagi setelah pengelola mengaktifkannya.</div>
        </div>

        <div class="mt-10 flex flex-col gap-3 border-t border-sch-border pt-7 sm:flex-row sm:justify-between">
            <PublicButton v-if="project && step > 1" type="button" variant="outline" :disabled="form.processing" @click="previousStep">Kembali</PublicButton>
            <span v-else class="hidden sm:block"/>
            <PublicButton v-if="project && step < 4" type="button" arrow @click="nextStep">Lanjutkan</PublicButton>
            <PublicButton v-else type="submit" arrow :loading="form.processing" :disabled="!recaptchaSiteKey">{{ form.processing ? 'Mengirim permintaan…' : 'Kirim Permintaan' }}</PublicButton>
        </div>
    </form>
</template>

<style scoped>
@media (prefers-reduced-motion: no-preference) {
    .inquiry-step-content { animation: inquiry-step-enter 210ms ease-out both; }
}

@keyframes inquiry-step-enter {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
