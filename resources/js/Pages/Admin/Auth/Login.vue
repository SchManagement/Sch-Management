<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, nextTick, ref } from 'vue';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);
const errors = computed(() => Object.values(form.errors));

const submit = () => {
    form.post('/admin/login', {
        preserveScroll: true,
        onError: async () => {
            await nextTick();
            document.querySelector('[data-error-summary]')?.focus();
        },
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Admin Login | SCH Management">
        <meta name="robots" content="noindex, nofollow" />
    </Head>

    <a href="#admin-login-main" class="fixed left-4 top-4 z-50 -translate-y-24 rounded-lg bg-white px-4 py-3 font-semibold text-sch-navy shadow-lg focus:translate-y-0">Lewati ke formulir masuk</a>
    <main id="admin-login-main" tabindex="-1" class="relative isolate min-h-dvh overflow-hidden bg-sch-midnight text-white">
        <div
            class="pointer-events-none absolute inset-0 opacity-70"
            aria-hidden="true"
            style="background-image: radial-gradient(circle at 12% 18%, rgba(0, 140, 255, 0.2), transparent 34%), radial-gradient(circle at 86% 82%, rgba(0, 181, 255, 0.12), transparent 30%)"
        ></div>
        <div
            class="pointer-events-none absolute inset-0 opacity-[0.08]"
            aria-hidden="true"
            style="background-image: linear-gradient(rgba(255,255,255,.22) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.22) 1px, transparent 1px); background-size: 64px 64px"
        ></div>

        <div class="relative mx-auto grid min-h-dvh w-full max-w-7xl lg:grid-cols-[minmax(0,1fr)_minmax(420px,520px)]">
            <section class="hidden flex-col justify-between px-10 py-12 lg:flex xl:px-16 xl:py-16" aria-labelledby="admin-welcome-heading">
                <Link href="/" class="flex w-fit items-center gap-3 rounded-lg" aria-label="Kembali ke beranda SCH Management">
                    <span class="grid size-12 place-items-center rounded-xl bg-white text-sm font-extrabold tracking-[0.16em] text-sch-navy" aria-hidden="true">SCH</span>
                    <span>
                        <span class="block text-sm font-bold tracking-wide">SCH Management</span>
                        <span class="block text-xs font-medium text-blue-200">Admin Console</span>
                    </span>
                </Link>

                <div class="max-w-2xl pb-10">
                    <p class="mb-5 text-sm font-bold tracking-[0.18em] text-sch-cyan">SECURE ADMINISTRATION</p>
                    <p id="admin-welcome-heading" class="max-w-xl text-4xl font-bold leading-tight tracking-[-0.03em] text-balance xl:text-5xl">
                        Kelola fondasi digital SCH Management dengan aman.
                    </p>
                    <p class="mt-6 max-w-xl text-base leading-7 text-blue-100/80">
                        Area terbatas untuk administrator resmi. Seluruh akses dilindungi oleh autentikasi sesi dan otorisasi administrator.
                    </p>
                </div>

                <p class="text-xs font-semibold tracking-[0.14em] text-blue-200/70">WEB SOLUTIONS · IT SERVICES · DIGITAL GROWTH</p>
            </section>

            <section class="flex min-h-dvh items-center bg-sch-surface px-4 py-10 text-sch-ink sm:px-8 lg:px-10 xl:px-14" aria-labelledby="login-heading">
                <div class="mx-auto w-full max-w-md">
                    <Link href="/" class="mb-10 flex w-fit items-center gap-3 rounded-lg lg:hidden" aria-label="Kembali ke beranda SCH Management">
                        <span class="grid size-11 place-items-center rounded-xl bg-sch-navy text-xs font-extrabold tracking-[0.16em] text-white" aria-hidden="true">SCH</span>
                        <span>
                            <span class="block text-sm font-bold text-sch-navy">SCH Management</span>
                            <span class="block text-xs font-medium text-sch-muted">Admin Console</span>
                        </span>
                    </Link>

                    <div class="mb-8">
                        <p class="mb-3 text-sm font-bold tracking-[0.16em] text-sch-blue">AKSES ADMINISTRATOR</p>
                        <h1 id="login-heading" class="text-3xl font-bold tracking-[-0.03em] text-sch-navy sm:text-4xl">Selamat datang kembali.</h1>
                        <p class="mt-3 text-base leading-7 text-sch-muted">Masuk menggunakan akun administrator SCH Management.</p>
                    </div>

                    <div
                        v-if="errors.length"
                        ref="errorSummary"
                        data-error-summary
                        tabindex="-1"
                        role="alert"
                        class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm leading-6 text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500"
                    >
                        <p class="font-semibold">Login belum berhasil.</p>
                        <p>Periksa kembali informasi di bawah dan coba lagi.</p>
                    </div>

                    <form class="space-y-5" novalidate @submit.prevent="submit">
                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-sch-navy">
                                Email <span class="text-red-700" aria-hidden="true">*</span>
                            </label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                name="email"
                                autocomplete="username"
                                inputmode="email"
                                required
                                autofocus
                                :aria-invalid="Boolean(form.errors.email)"
                                :aria-describedby="form.errors.email ? 'email-error' : undefined"
                                class="min-h-12 w-full rounded-xl border bg-white px-4 text-base text-sch-ink shadow-sm outline-none transition-[border-color,box-shadow] placeholder:text-slate-400 focus:border-sch-blue focus:ring-4 focus:ring-blue-100"
                                :class="form.errors.email ? 'border-red-500' : 'border-sch-border'"
                                placeholder="nama@perusahaan.com"
                            />
                            <p v-if="form.errors.email" id="email-error" class="mt-2 text-sm font-medium leading-5 text-red-700">
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-sch-navy">
                                Kata sandi <span class="text-red-700" aria-hidden="true">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    id="password"
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password"
                                    autocomplete="current-password"
                                    required
                                    :aria-invalid="Boolean(form.errors.password)"
                                    :aria-describedby="form.errors.password ? 'password-error' : undefined"
                                    class="min-h-12 w-full rounded-xl border bg-white px-4 pr-14 text-base text-sch-ink shadow-sm outline-none transition-[border-color,box-shadow] placeholder:text-slate-400 focus:border-sch-blue focus:ring-4 focus:ring-blue-100"
                                    :class="form.errors.password ? 'border-red-500' : 'border-sch-border'"
                                    placeholder="Masukkan kata sandi"
                                />
                                <button
                                    type="button"
                                    class="absolute inset-y-0 right-1 my-auto grid size-11 place-items-center rounded-lg text-sch-muted transition-colors hover:bg-sch-soft-blue hover:text-sch-blue active:bg-blue-100"
                                    :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                                    :aria-pressed="showPassword"
                                    @click="showPassword = !showPassword"
                                >
                                    <svg v-if="!showPassword" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.5-6 9.75-6 9.75 6 9.75 6-3.5 6-9.75 6S2.25 12 2.25 12Z" />
                                        <circle cx="12" cy="12" r="2.75" />
                                    </svg>
                                    <svg v-else class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 10.7a2 2 0 0 0 2.7 2.7M9.9 5.4A10.8 10.8 0 0 1 12 5.2c6.2 0 9.5 6.8 9.5 6.8a15 15 0 0 1-2.1 2.8M6.2 6.3C3.8 8 2.5 12 2.5 12s3.3 6.8 9.5 6.8c1.5 0 2.8-.4 3.9-1" />
                                    </svg>
                                </button>
                            </div>
                            <p v-if="form.errors.password" id="password-error" class="mt-2 text-sm font-medium leading-5 text-red-700">
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <label class="flex min-h-11 cursor-pointer items-center gap-3 rounded-lg text-sm font-medium text-sch-ink">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                name="remember"
                                class="size-5 rounded border-sch-border text-sch-blue accent-sch-blue"
                            />
                            <span>Ingat sesi saya di perangkat ini</span>
                        </label>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-sch-blue px-5 text-base font-bold text-white shadow-[0_10px_24px_rgba(0,108,255,0.22)] transition-[background-color,box-shadow] hover:bg-blue-700 hover:shadow-[0_12px_28px_rgba(0,108,255,0.28)] active:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-50 disabled:shadow-none"
                        >
                            <svg v-if="form.processing" class="size-5 animate-spin motion-reduce:animate-none" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle class="opacity-30" cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3" />
                                <path class="opacity-90" fill="currentColor" d="M21 12a9 9 0 0 0-9-9v3a6 6 0 0 1 6 6h3Z" />
                            </svg>
                            <span>{{ form.processing ? 'Memverifikasi…' : 'Masuk ke Dashboard' }}</span>
                        </button>
                    </form>

                    <p class="mt-8 border-t border-sch-border pt-6 text-center text-sm leading-6 text-sch-muted">
                        Akses terbatas untuk administrator resmi SCH Management.
                    </p>
                </div>
            </section>
        </div>
    </main>
</template>
