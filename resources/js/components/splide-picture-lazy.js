/**
 * Splide picture lazy-load plugin.
 *
 * Splide's built-in `lazyLoad` only knows about `<img>` tags carrying
 * `data-splide-lazy*` attributes — which means dropping our `<picture>`
 * markup and losing webp/avif format negotiation per slide. This
 * helper bridges the gap: on Splide mount it stashes every `<source
 * srcset>` and `<img src/srcset>` inside a slide into matching
 * `data-splide-lazy*` attributes, so the browser doesn't fetch them.
 * As Splide pages move, slides whose visibility (with the slider's
 * configured `preloadPages` margin) flips on get their attributes
 * restored — at which point the browser performs its normal
 * `<picture>` selection and decode. Off-screen slides stay deferred
 * until they're approached.
 *
 * Usage:
 *
 *     import { pictureLazyLoad } from './splide-picture-lazy.js';
 *     this.splide = new Splide(el, { ...config });
 *     pictureLazyLoad(this.splide);
 *     this.splide.mount();
 *
 * Designed to coexist with the project's `data-splide-lazy` attribute
 * model — if a slide already opts in by hand, this helper leaves it
 * alone.
 */
export function pictureLazyLoad(splide) {
    let stashed = false;

    splide.on('mounted', () => {
        stashSlides();
        stashed = true;
        revealVisible();
    });

    splide.on('move scrolled resized', () => {
        if (stashed) {
            revealVisible();
        }
    });

    function stashSlides() {
        splide.Components.Slides.get().forEach((slide) => {
            slide.slide.querySelectorAll('picture').forEach((pic) => {
                // Skip pictures whose <img> is marked as high-priority — those
                // are LCP candidates (e.g. the hero's first slide) where
                // strip-then-restore would cancel an in-flight fetch and hurt
                // the metric. They stay loaded normally; the rest of the
                // carousel still defers as expected.
                const img = pic.querySelector('img');
                if (img && img.getAttribute('fetchpriority') === 'high') return;

                pic.querySelectorAll('source[srcset]').forEach((src) => {
                    src.dataset.splideLazySrcset = src.srcset;
                    src.removeAttribute('srcset');
                });
                if (img && img.src) {
                    img.dataset.splideLazySrc = img.src;
                    img.removeAttribute('src');
                }
                if (img && img.srcset) {
                    img.dataset.splideLazySrcset = img.srcset;
                    img.removeAttribute('srcset');
                }
            });
        });
    }

    function revealVisible() {
        // Compute the visible index range from Splide's controller +
        // perPage instead of calling `slide.isVisible()` — the
        // SlideComponent objects returned from `Slides.get()` don't
        // reliably expose that method across versions / clones, so
        // direct index math is the safer access pattern. We preload
        // one page-worth of slides on either side of the active page
        // (matches Splide's `preloadPages: 1` default for its own
        // built-in lazy-load).
        const controller = splide.Components.Controller;
        const current = controller && typeof controller.getIndex === 'function'
            ? controller.getIndex()
            : 0;
        const perPage = Number(splide.options.perPage) || 1;
        const preload = 1;
        const from = Math.max(0, current - perPage * preload);
        const to = current + perPage * (preload + 1) - 1;

        splide.Components.Slides.get().forEach((slide) => {
            const index = slide.index;
            if (typeof index !== 'number' || index < from || index > to) return;

            const el = slide.slide;
            if (! el) return;

            el.querySelectorAll('source[data-splide-lazy-srcset]').forEach((src) => {
                src.srcset = src.dataset.splideLazySrcset;
                delete src.dataset.splideLazySrcset;
            });
            el.querySelectorAll('img[data-splide-lazy-src], img[data-splide-lazy-srcset]').forEach((img) => {
                if (img.dataset.splideLazySrc) {
                    img.src = img.dataset.splideLazySrc;
                    delete img.dataset.splideLazySrc;
                }
                if (img.dataset.splideLazySrcset) {
                    img.srcset = img.dataset.splideLazySrcset;
                    delete img.dataset.splideLazySrcset;
                }
            });
        });
    }
}
