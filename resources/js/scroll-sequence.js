/**
 * Generic Scroll-Scrubbing Image Sequence
 * ====================================================
 * Preloads PNG frames and renders them to a canvas
 * based on scroll position for a smooth "3D exploded view" experience.
 */

(function () {
    'use strict';

    // ─── Config ───
    const config = window.seqConfig || { 
        totalFrames: 100, 
        framePath: '/images/frames',
        prefix: 'kompos' 
    };
    const TOTAL_FRAMES = config.totalFrames;
    const FRAME_PATH = config.framePath;
    const pfx = config.prefix;

    // ─── DOM Elements ───
    const canvas = document.querySelector(`.${pfx}-canvas`);
    const ctx = canvas ? canvas.getContext('2d') : null;
    const scrollContainer = document.querySelector(`.${pfx}-scroll`);
    const loader = document.querySelector(`.${pfx}-loader`);
    const loadProgress = document.querySelector(`.${pfx}-loader__fill`);
    const loadPercent = document.querySelector(`.${pfx}-loader__percent`);
    const progressFill = document.querySelector(`.${pfx}-progress__fill`);
    const progressLabel = document.querySelector(`.${pfx}-progress__label`);
    const callouts = document.querySelector(`.${pfx}-callouts`);
    const scrollHint = document.querySelector(`.${pfx}-header__scroll-hint`) || document.querySelector(`.${pfx}-scroll-hint`);
    const header = document.querySelector(`.${pfx}-header`);
    const bottom = document.querySelector(`.${pfx}-bottom`);
    const fallback = document.querySelector(`.${pfx}-fallback`);

    // ─── State ───
    const frames = [];
    let currentFrame = -1;
    let isLoaded = false;
    let rafId = null;

    // Hide fallback in normal mode
    if (fallback) fallback.style.display = 'none';

    // ─── Frame URL Generator ───
    function getFrameUrl(index) {
        const num = String(index).padStart(3, '0');
        // Handle ezgif frames if needed (fallback to direct format)
        return `${FRAME_PATH}/frame-${num}.png`;
    }

    // ─── Preload All Frames ───
    function preloadFrames() {
        return new Promise((resolve) => {
            let loaded = 0;

            for (let i = 0; i < TOTAL_FRAMES; i++) {
                const img = new Image();
                img.src = getFrameUrl(i);

                img.onload = img.onerror = () => {
                    loaded++;
                    const percent = Math.round((loaded / TOTAL_FRAMES) * 100);

                    if (loadProgress) loadProgress.style.width = percent + '%';
                    if (loadPercent) loadPercent.textContent = percent + '%';

                    if (loaded === TOTAL_FRAMES) {
                        resolve();
                    }
                };

                frames[i] = img;
            }
        });
    }

    // ─── Canvas Setup (DPR-aware, no cumulative scale) ───
    function setupCanvas() {
        if (!canvas || !ctx) return;

        const dpr = window.devicePixelRatio || 1;
        const w = window.innerWidth;
        const h = window.innerHeight;

        // Set display size
        canvas.style.width = w + 'px';
        canvas.style.height = h + 'px';

        // Set actual buffer size (scaled by DPR)
        canvas.width = w * dpr;
        canvas.height = h * dpr;
    }

    // ─── Draw Frame (handles DPR correctly) ───
    function drawFrame(frameIndex) {
        if (!ctx || !frames[frameIndex] || !frames[frameIndex].complete) return;

        const img = frames[frameIndex];
        const bufW = canvas.width;
        const bufH = canvas.height;

        // Clear the full buffer
        ctx.clearRect(0, 0, bufW, bufH);

        // Object-fit: cover calculation on the buffer dimensions
        const imgRatio = img.naturalWidth / img.naturalHeight;
        const canvasRatio = bufW / bufH;

        let drawW, drawH, drawX, drawY;

        if (canvasRatio > imgRatio) {
            // Canvas is wider — fit to width
            drawW = bufW;
            drawH = bufW / imgRatio;
            drawX = 0;
            drawY = (bufH - drawH) / 2;
        } else {
            // Canvas is taller — fit to height
            drawH = bufH;
            drawW = bufH * imgRatio;
            drawX = (bufW - drawW) / 2;
            drawY = 0;
        }

        ctx.drawImage(img, drawX, drawY, drawW, drawH);
    }

    // ─── Scroll Update (called via rAF loop) ───
    function updateFrame() {
        if (!isLoaded || !scrollContainer) return;

        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const docHeight = scrollContainer.scrollHeight - window.innerHeight;

        // Guard: avoid division by zero
        if (docHeight <= 0) return;

        const scrollFraction = Math.max(0, Math.min(1, scrollTop / docHeight));

        // Map scroll 0–1 → frame 0–(N-1)
        const frameIndex = Math.min(
            TOTAL_FRAMES - 1,
            Math.floor(scrollFraction * (TOTAL_FRAMES - 1))
        );

        // Only redraw when the frame actually changes
        if (frameIndex !== currentFrame) {
            currentFrame = frameIndex;
            drawFrame(currentFrame);
        }

        // Update progress indicator
        const percent = Math.round(scrollFraction * 100);
        if (progressFill) progressFill.style.height = percent + '%';
        if (progressLabel) progressLabel.textContent = percent + '%';

        // Fade out header (gone by 20% scroll)
        if (header) {
            const headerOpacity = Math.max(0, 1 - scrollFraction * 5);
            header.style.opacity = headerOpacity;
            header.style.pointerEvents = headerOpacity < 0.1 ? 'none' : 'auto';
        }

        // Hide scroll hint immediately
        if (scrollHint) {
            scrollHint.style.opacity = scrollFraction > 0.02 ? '0' : '1';
        }

        // Show callouts when near the end (>80%)
        if (callouts) {
            callouts.classList.toggle(`${pfx}-callouts--visible`, scrollFraction > 0.80);
        }

        // Show bottom CTA at >92%
        if (bottom) {
            bottom.classList.toggle(`${pfx}-bottom--visible`, scrollFraction > 0.92);
        }
    }

    // ─── rAF Loop (replaces scroll event for smoother updates) ───
    function loop() {
        updateFrame();
        rafId = requestAnimationFrame(loop);
    }

    // ─── Resize Handler ───
    function onResize() {
        setupCanvas();
        if (isLoaded && frames[Math.max(0, currentFrame)]) {
            drawFrame(Math.max(0, currentFrame));
        }
    }

    // ─── Init ───
    async function init() {
        setupCanvas();

        // Show loader
        if (loader) loader.classList.add(`${pfx}-loader--active`);

        // Preload all frames
        await preloadFrames();

        isLoaded = true;

        // Draw first frame
        currentFrame = 0;
        drawFrame(0);

        // Hide loader
        if (loader) {
            loader.classList.add(`${pfx}-loader--done`);
            setTimeout(() => {
                loader.style.display = 'none';
            }, 600);
        }

        // Force scroll reset to top on page load
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
        window.scrollTo(0, 0);

        // Start the rAF loop (smoother than scroll events)
        loop();

        // Resize handler
        window.addEventListener('resize', onResize, { passive: true });
    }

    // ─── Start ───
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
