import { nextTick, onBeforeUnmount, onMounted, watch } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

let registered = false;
const loaderKey = 'sch-motion-intro-seen';

function reducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

export function introPending() {
    if (typeof window === 'undefined') return false;
    try {
        return !sessionStorage.getItem(loaderKey);
    } catch {
        return false;
    }
}

function rememberIntro() {
    if (!introPending()) return false;
    try {
        sessionStorage.setItem(loaderKey, '1');
        return true;
    } catch {
        // Storage may be disabled; a blocked loader is worse than skipping it.
        return false;
    }
}

export function usePublicMotion(main, loader, showLoader, page) {
    let context;
    let loaderTimeline;
    let pointerCleanup;
    let motionPreference;
    let preferenceChange;
    let runId = 0;

    const cleanup = () => {
        context?.revert();
        context = null;
        loaderTimeline?.kill();
        loaderTimeline = null;
        pointerCleanup?.();
        pointerCleanup = null;
    };

    const run = async (intro = false) => {
        const currentRun = ++runId;
        cleanup();
        showLoader.value = intro;
        await nextTick();
        if (currentRun !== runId || !main.value) return;

        if (reducedMotion()) {
            if (intro && loader.value) {
                loaderTimeline = gsap.to(loader.value, {
                    autoAlpha: 0, duration: 0.16, delay: 0.08,
                    onComplete: () => { showLoader.value = false; loaderTimeline = null; },
                });
            } else showLoader.value = false;
            return;
        }

        if (!registered) {
            gsap.registerPlugin(ScrollTrigger);
            registered = true;
        }

        const root = main.value;
        const home = root.querySelector('[data-home-section="hero"]');
        const firstHeading = root.querySelector('h1');
        const firstSection = root.querySelector('section, article > header');
        const compact = window.matchMedia('(max-width: 639px)').matches;
        const travel = compact ? 16 : 28;
        const reveal = (target, options = {}) => {
            if (!target) return;
            gsap.fromTo(target,
                { autoAlpha: 0, y: options.y ?? travel },
                {
                    autoAlpha: 1, y: 0, duration: options.duration ?? 0.7,
                    ease: 'power2.out', clearProps: 'transform,opacity,visibility',
                    scrollTrigger: { trigger: options.trigger || target, start: 'top 88%', once: true },
                });
        };

        context = gsap.context(() => {
            // A fast page-enter is the Inertia transition. The full loader is session-only.
            gsap.fromTo(root, { autoAlpha: 0, y: compact ? 5 : 9 }, {
                autoAlpha: 1, y: 0, duration: intro ? 0.45 : 0.3,
                ease: 'power2.out', clearProps: 'transform,opacity,visibility',
            });

            if (home) {
                const parts = [
                    '[data-hero-eyebrow]', '[data-hero-heading]', '[data-hero-description]',
                    '[data-hero-actions]', '[data-hero-support]',
                ].map(selector => home.querySelector(selector)).filter(Boolean);
                gsap.fromTo(parts, { autoAlpha: 0, y: compact ? 13 : 24 }, {
                    autoAlpha: 1, y: 0, duration: 0.72, stagger: compact ? 0.06 : 0.1,
                    ease: 'power2.out', clearProps: 'transform,opacity,visibility',
                    delay: intro ? 1.02 : 0,
                });
                const visual = home.querySelector('[data-hero-visual]');
                if (visual) {
                    gsap.fromTo(visual, { autoAlpha: 0, y: 15, scale: 0.97 }, {
                        autoAlpha: 1, y: 0, scale: 1, duration: 0.95, delay: intro ? 1.24 : 0.2,
                        ease: 'power2.out', clearProps: 'transform,opacity,visibility',
                    });
                    if (window.matchMedia('(min-width: 1024px) and (pointer: fine)').matches) {
                        const art = visual.querySelector('.hero-art');
                        if (art) {
                            const moveX = gsap.quickTo(art, 'x', { duration: 0.6, ease: 'power2.out' });
                            const moveY = gsap.quickTo(art, 'y', { duration: 0.6, ease: 'power2.out' });
                            let bounds;
                            const enter = () => { bounds = visual.getBoundingClientRect(); };
                            const move = event => {
                                if (visual.querySelector('[data-three-visual].is-ready')) {
                                    moveX(0);
                                    moveY(0);
                                    return;
                                }
                                if (!bounds) enter();
                                moveX(((event.clientX - bounds.left) / bounds.width - 0.5) * 14);
                                moveY(((event.clientY - bounds.top) / bounds.height - 0.5) * 14);
                            };
                            const reset = () => { bounds = null; moveX(0); moveY(0); };
                            visual.addEventListener('pointerenter', enter, { passive: true });
                            visual.addEventListener('pointermove', move, { passive: true });
                            visual.addEventListener('pointerleave', reset);
                            pointerCleanup = () => {
                                visual.removeEventListener('pointerenter', enter);
                                visual.removeEventListener('pointermove', move);
                                visual.removeEventListener('pointerleave', reset);
                            };
                        }
                    }
                }
            } else if (firstHeading) {
                const targets = [
                    firstSection?.querySelector('nav[aria-label="Breadcrumb"]'),
                    firstSection?.querySelector('p.text-xs'),
                    firstHeading,
                    firstHeading.nextElementSibling?.matches('p') ? firstHeading.nextElementSibling : null,
                ].filter(Boolean);
                gsap.fromTo([...new Set(targets)], { autoAlpha: 0, y: compact ? 10 : 18 }, {
                    autoAlpha: 1, y: 0, duration: 0.57, stagger: 0.07,
                    ease: 'power2.out', clearProps: 'transform,opacity,visibility',
                    delay: intro ? 1.02 : 0,
                });
            }

            root.querySelectorAll('section:not([data-home-section="hero"])').forEach(section => {
                const heading = section.querySelector('h2');
                if (heading && !heading.closest('[data-reveal]')) reveal(heading, { trigger: section, y: compact ? 12 : 22 });
            });

            root.querySelectorAll('[data-reveal]').forEach(element => {
                if (element.closest('[data-home-section="hero"]') || element.contains(firstHeading)) return;
                reveal(element);
            });

            root.querySelectorAll('[data-stagger]').forEach(group => {
                if (group.matches('[data-stagger="process"]')) return;
                const items = [...group.children].slice(0, 12);
                if (!items.length) return;
                gsap.fromTo(items, { autoAlpha: 0, y: compact ? 12 : 24 }, {
                    autoAlpha: 1, y: 0, duration: compact ? 0.5 : 0.65,
                    stagger: { amount: Math.min(compact ? 0.2 : 0.34, items.length * 0.07) },
                    ease: 'power2.out', clearProps: 'transform,opacity,visibility',
                    scrollTrigger: { trigger: group, start: 'top 88%', once: true },
                });
            });

            // Subpage collections have no CMS-specific animation code.
            for (const selector of ['[data-project-card]', '[data-article-card]']) {
                root.querySelectorAll(selector).forEach(card => {
                    if (card.closest('[data-stagger]') || card.closest('[data-home-section="hero"]')) return;
                    reveal(card, { y: compact ? 12 : 20, duration: 0.6 });
                });
            }

            const featured = root.querySelector('[data-featured-article]');
            if (featured) reveal(featured, { y: compact ? 12 : 22, duration: 0.8 });
            [...root.querySelectorAll('[data-project-gallery] img, [data-project-hero] img, [data-image-reveal] img')].slice(0, 10).forEach(image => {
                reveal(image, { y: compact ? 8 : 18, duration: 0.8 });
            });

            const rail = root.querySelector('[data-stagger="process"]');
            if (rail) {
                const progress = rail.parentElement?.querySelector('[data-process-progress]');
                if (progress) {
                    gsap.fromTo(progress, { scaleY: 0, scaleX: 0 }, {
                        scaleY: 1, scaleX: 1, ease: 'none',
                        scrollTrigger: { trigger: rail, start: 'top 82%', end: 'bottom 55%', scrub: 0.35 },
                    });
                }
                rail.querySelectorAll('[data-process-step]').forEach(step => reveal(step, {
                    y: compact ? 10 : 16, duration: 0.55,
                }));
            }
        }, root);

        if (intro && loader.value) {
            const pieces = [...loader.value.querySelectorAll('[data-loader-piece]')];
            const lock = loader.value.querySelector('[data-loader-lock]');
            const sweep = loader.value.querySelector('[data-loader-sweep]');
            loaderTimeline = gsap.timeline({ onComplete: () => {
                showLoader.value = false;
                loaderTimeline = null;
                ScrollTrigger.refresh();
            } });
            loaderTimeline.fromTo(pieces, {
                autoAlpha: 0, x: index => [-9, -15, 6, 14][index],
                y: index => [-11, 1, 11, 0][index],
            }, {
                autoAlpha: 1, x: 0, y: 0, duration: 0.43, stagger: 0.065, ease: 'power2.out',
            }).to(lock, { autoAlpha: 1, duration: 0.16, ease: 'power1.out' }, '-=0.08')
                .fromTo(sweep, { xPercent: -125 }, { xPercent: 125, duration: 0.32, ease: 'power2.inOut' }, '-=0.04')
                .to(loader.value, { autoAlpha: 0, duration: 0.28, ease: 'power2.out' }, '+=0.06');
        } else {
            showLoader.value = false;
        }
    };

    onMounted(() => {
        motionPreference = window.matchMedia('(prefers-reduced-motion: reduce)');
        preferenceChange = () => run(false).catch(() => { showLoader.value = false; cleanup(); });
        motionPreference.addEventListener('change', preferenceChange);
        run(rememberIntro()).catch(() => { showLoader.value = false; cleanup(); });
    });
    watch(() => page.url, () => run(false).catch(() => { showLoader.value = false; cleanup(); }));
    onBeforeUnmount(() => {
        runId++;
        motionPreference?.removeEventListener('change', preferenceChange);
        cleanup();
    });
}
