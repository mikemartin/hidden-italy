/**
 * Sticky tour sub-nav — owns the smooth-scroll affordance and keeps
 * the active tab visible inside the horizontally-scrollable nav row.
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
        /* Centre the active button in the horizontal scroll container so
           the spy never strands the indicator off-screen on narrow
           viewports. `block: 'nearest'` keeps the page from scrolling
           vertically — the sticky nav is always visible. */
        ensureActiveInView() {
            const active = this.$store.tourSubnav.active;
            const btn = this.$el.querySelector(`[data-subnav-id="${active}"]`);
            btn?.scrollIntoView({ block: 'nearest', inline: 'center', behavior: 'smooth' });
        },
    };
}
