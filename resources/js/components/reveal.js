/**
 * `x-reveal` — entrance animation directive.
 *
 * Put `x-reveal` on a container and `data-reveal-item` on each descendant
 * you want to fade and slide up. The descendants animate together with a
 * stagger when the container crosses ~15% into the viewport from the
 * bottom, then the observer disconnects (once-only).
 *
 * Container-level tuning (on the `x-reveal` element):
 *   data-reveal-stagger="120"      — ms between items (default 80)
 *   data-reveal-row-stagger="80"   — ms between ROWS instead of items, for
 *                                    heavy grids where per-item stagger
 *                                    would exceed the 500ms budget. Rows
 *                                    are detected by Y-position at reveal
 *                                    time, so responsive grids work
 *                                    automatically. Overrides `stagger`.
 *   data-reveal-delay="200"        — ms before the first item starts
 *                                    (default 0)
 *
 * Per-item tuning (on each `[data-reveal-item]`):
 *   data-reveal-preset="hero-h1"   — pick a named motion preset (see PRESETS)
 *
 * Available presets:
 *   hero-h1   — page-defining headlines (home hero, tour banner H1)
 *   h1        — sub-page section headlines (CTA banners)
 *   chip      — small label-style elements (region pills, eyebrows)
 *   card      — items in card grids (tour cards, profile cards)
 *   (default) — anything without a preset uses the base 520ms / 16px
 *
 * Buttons and other CTAs intentionally have no preset — primary actions
 * stay statically present from page load rather than arriving with
 * motion, since animation on an action creates a perceived "wait
 * before clickable" moment. Consistent across hero/banner/page-builder
 * CTAs site-wide.
 *
 * Token mirror
 * ------------
 * Values here mirror the brand motion tokens declared in `site.css`:
 *   EASE_STANDARD  ↔  --ease-standard      cubic-bezier(0.4, 0, 0.2, 1)
 *   380ms          ↔  --duration-quick     (chip)
 *   520ms          ↔  --duration-standard  (h1, default)
 *   600ms          ↔  --duration-slow      (hero-h1)
 *   440ms             card — non-tokenised, sits between quick + standard
 *
 * Keep both files in sync when retuning the brand motion identity.
 *
 * Honours `prefers-reduced-motion: reduce` by skipping the animation and
 * clearing the initial hidden styles so the content is visible immediately.
 * The initial hidden styles are defined in `site.css`.
 */
import { animate } from 'motion/mini';

const EASE_STANDARD = [0.4, 0, 0.2, 1];

const PRESETS = {
    'hero-h1': { duration: 600, distance: 32 },
    'h1':      { duration: 520, distance: 24 },
    'chip':    { duration: 380, distance: 10 },
    'card':    { duration: 440, distance: 16 },
};

const DEFAULTS = { duration: 520, distance: 16 };
const DEFAULT_STAGGER = 80;
const REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)');

/**
 * Group items by their on-screen Y position and return the row index for
 * each item. Y is rounded to a 5px tolerance so subpixel positioning
 * doesn't fragment a single row into multiple "rows".
 */
function computeRowIndices(items) {
    const tops = [];
    items.forEach((item) => {
        tops.push(Math.round(item.getBoundingClientRect().top / 5) * 5);
    });
    const uniqueTops = [...new Set(tops)].sort((a, b) => a - b);
    return tops.map((top) => uniqueTops.indexOf(top));
}

export default function registerReveal(Alpine) {
    Alpine.directive('reveal', (el) => {
        const items = el.querySelectorAll('[data-reveal-item]');
        if (!items.length) return;

        if (REDUCED.matches) {
            items.forEach((item) => {
                item.style.opacity = '1';
                item.style.transform = 'none';
                item.style.willChange = 'auto';
            });
            return;
        }

        const rowStaggerMs = Number(el.dataset.revealRowStagger ?? 0);
        const useRowStagger = rowStaggerMs > 0;
        const stagger = useRowStagger
            ? rowStaggerMs / 1000
            : Number(el.dataset.revealStagger ?? DEFAULT_STAGGER) / 1000;
        const baseDelay = Number(el.dataset.revealDelay ?? 0) / 1000;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                observer.disconnect();

                const staggerIndices = useRowStagger
                    ? computeRowIndices(items)
                    : Array.from({ length: items.length }, (_, i) => i);

                items.forEach((item, i) => {
                    const preset = PRESETS[item.dataset.revealPreset] ?? DEFAULTS;
                    const duration = preset.duration / 1000;
                    const distance = preset.distance;
                    const controls = animate(
                        item,
                        { opacity: [0, 1], transform: [`translateY(${distance}px)`, 'translateY(0)'] },
                        { duration, delay: baseDelay + staggerIndices[i] * stagger, easing: EASE_STANDARD },
                    );
                    controls.finished
                        .then(() => { item.style.willChange = 'auto'; })
                        .catch(() => {});
                });
            });
        }, { rootMargin: '0px 0px -15% 0px', threshold: 0 });

        observer.observe(el);
    });
}
