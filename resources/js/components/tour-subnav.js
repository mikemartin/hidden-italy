/**
 * Sticky tour sub-nav — only owns the smooth-scroll affordance now.
 * Active-section tracking is handled by `x-intersect` directives on
 * each section partial, which write into the `tourSubnav` Alpine
 * store (registered in `site.js`).
 *
 * Smooth scroll uses native `scrollIntoView`, which honours the
 * `scroll-mt-*` utility set on each section partial.
 */
export default function tourSubnav() {
    return {
        goTo(id) {
            const el = document.getElementById(id);
            if (!el) return;
            // Set active immediately and lock the intersect spy for the
            // duration of the smooth scroll — sections passing through
            // the band during transit would otherwise overwrite the
            // clicked target with whatever section the band passed last.
            this.$store.tourSubnav.select(id);
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        },
    };
}
