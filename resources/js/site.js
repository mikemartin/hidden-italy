import Splide from '@splidejs/splide';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import intersect from '@alpinejs/intersect';
import tippy from 'tippy.js';
import simpleLikes from './components/simple-likes.js';
import tourMap from './components/tour-map.js';
import tourSubnav from './components/tour-subnav.js';

window.Splide = Splide;
window.simpleLikes = simpleLikes;

document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(collapse);
    window.Alpine.plugin(focus);
    window.Alpine.plugin(intersect);

    /* Sticky tour sub-nav state.
       `active`   — id of the currently-on section, read by the nav buttons
                    for their active underline.
       `stuck`    — true once the nav has pinned to the top of the viewport;
                    flipped by a sentinel above the nav, read by the nav
                    for its drop-shadow.
       `select()` — called from a nav-button click. Sets `active`
                    immediately and locks `spy()` for ~800ms so intersect
                    events firing during the smooth scroll can't overwrite
                    the clicked target. The clicked section's own sentinel
                    won't necessarily land inside the spy band after the
                    scroll settles (the band is just below the nav, but
                    the sentinel is at content-top below the section's
                    own padding) — without this lock, active would snap
                    to whichever sentinel last passed through the band
                    during transit (typically the section just before).
       `spy()`    — called from per-section `x-intersect` directives.
                    No-op while the click lock is in effect. */
    window.Alpine.store('tourSubnav', {
        active: 'overview',
        stuck: false,
        _lockUntil: 0,
        select(id) {
            this.active = id;
            this._lockUntil = Date.now() + 800;
        },
        spy(id) {
            if (Date.now() < this._lockUntil) return;
            this.active = id;
        },
    });

    window.Alpine.store('wishlist', {
        count: 0,
        popping: false,

        async refresh(animate = false) {
            const res = await fetch('/!/simple-likes/wishlist');
            const data = await res.json();
            this.count = data.count;

            if (animate) {
                this.popping = true;
                setTimeout(() => {
                    this.popping = false;
                }, 200);
            }
        }
    });

    window.Alpine.store('nav', {
        mobileOpen: false,
        toggle() { this.mobileOpen = !this.mobileOpen; },
        close() { this.mobileOpen = false; },
    });

    window.Alpine.data('simpleLikes', simpleLikes);
    window.Alpine.data('tourMap', tourMap);
    window.Alpine.data('tourSubnav', tourSubnav);

    window.Alpine.directive('tooltip', (el, { expression }) => {
        const content = expression || el.getAttribute('data-tooltip') || '';
        if (!content) return;
        const theme = el.getAttribute('data-tooltip-theme') || undefined;
        tippy(el, { content, allowHTML: false, theme });
    });

    window.Alpine.magic('tooltip', el => message => {
        const instance = tippy(el, { content: message, trigger: 'manual' });
        instance.show();
        setTimeout(() => {
            instance.hide();
            setTimeout(() => instance.destroy(), 150);
        }, 2000);
    });
});

document.addEventListener('alpine:initialized', () => {
    window.Alpine.store('wishlist').refresh();
});

document.addEventListener('livewire:morph', () => {
    if (window.SimpleLikesBatch) {
        window.SimpleLikesBatch.cache = {};
    }
});
