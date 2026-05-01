import Splide from '@splidejs/splide';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import collapse from '@alpinejs/collapse';
import simpleLikes from './components/simple-likes.js';

window.Splide = Splide;
window.simpleLikes = simpleLikes;

document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(collapse);

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

    // Tippy.js — `x-tooltip` for hover/focus tooltips, `$tooltip` magic for
    // imperative one-off messages. Pulls content from the directive value or
    // expression; falls back to the host element's `data-tooltip` attribute.
    window.Alpine.directive('tooltip', (el, { expression }) => {
        const content = expression || el.getAttribute('data-tooltip') || '';
        if (!content) return;
        tippy(el, { content, allowHTML: false });
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
