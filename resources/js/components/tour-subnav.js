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
        /* Centre the active button inside the horizontal tab row so the
           spy never strands the indicator off-screen on narrow viewports.
           Drives `list.scrollTo` directly instead of `scrollIntoView` so
           only the nav's horizontal scroll moves — `scrollIntoView` would
           also tug the document scroll vertically, fighting the user's
           page scroll as new sections crossed the spy band. */
        ensureActiveInView() {
            const active = this.$store.tourSubnav.active;
            const btn = this.$el.querySelector(`[data-subnav-id="${active}"]`);
            if (!btn) return;
            const list = this.$el.querySelector('ul');
            if (!list || list.scrollWidth <= list.clientWidth) return;
            const listRect = list.getBoundingClientRect();
            const btnRect = btn.getBoundingClientRect();
            const btnLeftInList = btnRect.left - listRect.left + list.scrollLeft;
            const target = btnLeftInList - (list.clientWidth - btnRect.width) / 2;
            list.scrollTo({ left: target, behavior: 'smooth' });
        },
    };
}
