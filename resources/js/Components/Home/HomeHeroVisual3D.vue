<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

const emit = defineEmits(['ready', 'fallback']);
const mount = ref(null);
const ready = ref(false);

let renderer;
let scene;
let camera;
let structure;
let disposeResources;
let frameId = null;
let lastFrame = 0;
let loading = false;
let failed = false;
let visible = false;
let destroyed = false;
let generation = 0;
let viewportObserver;
let resizeObserver;
let mobileScreen;
let tabletScreen;
let reducedMotion;
let finePointer;
let pointerSurface;
let pointerBounds;

const pointerTarget = { x: 0, y: 0 };
const neutralRotation = { x: -0.10, y: -0.17 };

function eligible() {
    return !reducedMotion.matches && !navigator.connection?.saveData
        && !(mobileScreen.matches && navigator.deviceMemory && navigator.deviceMemory <= 2);
}

function stopLoop() {
    if (frameId !== null) cancelAnimationFrame(frameId);
    frameId = null;
    lastFrame = 0;
}

function resetPointer() {
    pointerBounds = null;
    pointerTarget.x = 0;
    pointerTarget.y = 0;
}

function removePointer() {
    pointerSurface?.removeEventListener('pointermove', trackPointer);
    pointerSurface?.removeEventListener('pointerleave', resetPointer);
    pointerSurface = null;
    resetPointer();
}

function disposeScene(releaseContext = true) {
    generation++;
    loading = false;
    ready.value = false;
    stopLoop();
    removePointer();
    renderer?.domElement.removeEventListener('webglcontextlost', onContextLost);
    if (disposeResources) disposeResources({ scene, renderer }, releaseContext);
    renderer = null;
    scene = null;
    camera = null;
    structure = null;
    disposeResources = null;
}

function fallback() {
    failed = true;
    disposeScene();
    if (!destroyed) emit('fallback');
}

function onContextLost(event) {
    event.preventDefault();
    failed = true;
    disposeScene(false);
    if (!destroyed) emit('fallback');
}

function draw() {
    if (!renderer || !scene || !camera) return;
    try {
        renderer.render(scene, camera);
    } catch {
        fallback();
    }
}

function resize() {
    if (!renderer || !camera || !mount.value) return;
    const width = mount.value.clientWidth;
    const height = mount.value.clientHeight;
    if (!width || !height) return;
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, mobileScreen.matches ? 1.25 : tabletScreen.matches ? 1.6 : 2));
    renderer.setSize(width, height, false);
    camera.aspect = width / height;
    camera.updateProjectionMatrix();
    if (frameId === null) draw();
}

function frame(now) {
    frameId = requestAnimationFrame(frame);
    const interval = mobileScreen.matches ? 1000 / 22 : tabletScreen.matches ? 1000 / 30 : 1000 / 45;
    if (lastFrame && now - lastFrame < interval) return;
    const delta = lastFrame ? Math.min((now - lastFrame) / 1000, 0.05) : 0.016;
    lastFrame = now;
    const ease = 1 - Math.exp(-delta * 2.8);
    structure.rotation.x += (neutralRotation.x + pointerTarget.x - structure.rotation.x) * ease;
    structure.rotation.y += (neutralRotation.y + pointerTarget.y - structure.rotation.y) * ease;
    structure.position.y = structure.userData.baseY
        + Math.sin(now * 0.00032) * (mobileScreen.matches ? 0.009 : tabletScreen.matches ? 0.016 : 0.024);
    draw();
}

function syncLoop() {
    if (!renderer || !eligible() || !visible || document.hidden) {
        stopLoop();
        return;
    }
    if (frameId === null) frameId = requestAnimationFrame(frame);
}

function trackPointer(event) {
    if (!finePointer.matches || !eligible() || !pointerSurface) return;
    if (!pointerBounds) pointerBounds = pointerSurface.getBoundingClientRect();
    const x = (event.clientX - pointerBounds.left) / pointerBounds.width - 0.5;
    const y = (event.clientY - pointerBounds.top) / pointerBounds.height - 0.5;
    pointerTarget.x = Math.max(-0.055, Math.min(0.055, -y * 0.11));
    pointerTarget.y = Math.max(-0.075, Math.min(0.075, x * 0.15));
}

function supportsWebGL() {
    try {
        const probe = document.createElement('canvas');
        const context = probe.getContext('webgl2') || probe.getContext('webgl');
        if (!context) return false;
        context.getExtension('WEBGL_lose_context')?.loseContext();
        return true;
    } catch {
        return false;
    }
}

async function load() {
    if (loading || renderer || failed || destroyed || !visible || !eligible()) return;
    loading = true;
    const ticket = ++generation;
    try {
        if (!supportsWebGL()) {
            fallback();
            return;
        }
        // Static named imports in this lazy module let Vite tree-shake unused Three code.
        const { createHeroScene, disposeHeroScene } = await import('./heroThreeScene');
        if (ticket !== generation || destroyed || !visible || !eligible()) return;
        const created = createHeroScene(mount.value, { compact: mobileScreen.matches });
        ({ renderer, scene, camera, structure } = created);
        disposeResources = disposeHeroScene;
        renderer.domElement.addEventListener('webglcontextlost', onContextLost);
        resize();
        draw();
        if (ticket !== generation || destroyed || !renderer) return;
        if (finePointer.matches) {
            pointerSurface = mount.value.closest('[data-home-section="hero"]');
            pointerSurface?.addEventListener('pointermove', trackPointer, { passive: true });
            pointerSurface?.addEventListener('pointerleave', resetPointer);
        }
        ready.value = true;
        emit('ready');
        syncLoop();
    } catch {
        if (ticket !== generation || destroyed) return;
        fallback();
    } finally {
        if (ticket === generation) loading = false;
    }
}

function capabilityChanged() {
    pointerBounds = null;
    disposeScene();
    emit('fallback');
    if (eligible() && visible) load();
}

onMounted(() => {
    mobileScreen = window.matchMedia('(max-width: 767px)');
    tabletScreen = window.matchMedia('(min-width: 768px) and (max-width: 1279px)');
    reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    finePointer = window.matchMedia('(min-width: 1280px) and (pointer: fine)');
    mobileScreen.addEventListener('change', capabilityChanged);
    tabletScreen.addEventListener('change', capabilityChanged);
    reducedMotion.addEventListener('change', capabilityChanged);
    finePointer.addEventListener('change', capabilityChanged);
    document.addEventListener('visibilitychange', syncLoop);

    if ('ResizeObserver' in window) {
        resizeObserver = new ResizeObserver(resize);
        resizeObserver.observe(mount.value);
    } else {
        window.addEventListener('resize', resize, { passive: true });
    }
    if ('IntersectionObserver' in window) {
        viewportObserver = new IntersectionObserver(entries => {
            visible = entries[0]?.isIntersecting ?? false;
            if (visible) { if (renderer) syncLoop(); else load(); }
            else stopLoop();
        }, { rootMargin: '80px 0px' });
        viewportObserver.observe(mount.value);
    } else {
        visible = true;
        load();
    }
});

onBeforeUnmount(() => {
    destroyed = true;
    viewportObserver?.disconnect();
    resizeObserver?.disconnect();
    window.removeEventListener('resize', resize);
    document.removeEventListener('visibilitychange', syncLoop);
    mobileScreen?.removeEventListener('change', capabilityChanged);
    tabletScreen?.removeEventListener('change', capabilityChanged);
    reducedMotion?.removeEventListener('change', capabilityChanged);
    finePointer?.removeEventListener('change', capabilityChanged);
    disposeScene();
});
</script>

<template>
    <div ref="mount" class="hero-three-canvas pointer-events-none absolute inset-0" :class="{ 'is-ready': ready }" data-three-visual aria-hidden="true"/>
</template>

<style scoped>
.hero-three-canvas { opacity: 0; transition: opacity 500ms var(--ease-sch-out); }
.hero-three-canvas.is-ready { opacity: 1; }
.hero-three-canvas :deep(canvas) { display: block; width: 100%; height: 100%; }
@media (prefers-reduced-motion: reduce) { .hero-three-canvas { transition: none; } }
</style>
