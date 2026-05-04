import Splide from '@splidejs/splide';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import tippy from 'tippy.js';
import simpleLikes from './components/simple-likes.js';

window.Splide = Splide;
window.simpleLikes = simpleLikes;

document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(collapse);
    window.Alpine.plugin(focus);

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
