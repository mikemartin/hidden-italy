import simpleLikes from './components/simple-likes.js';
import heroCarousel from './components/hero-carousel.js';
import toursCarousel from './components/tours-carousel.js';

window.simpleLikes = simpleLikes;

document.addEventListener('alpine:init', () => {
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
    window.Alpine.data('hero_carousel', heroCarousel);
    window.Alpine.data('tours_carousel', toursCarousel);
});

document.addEventListener('alpine:initialized', () => {
    window.Alpine.store('wishlist').refresh();
});

document.addEventListener('livewire:morph', () => {
    if (window.SimpleLikesBatch) {
        window.SimpleLikesBatch.cache = {};
    }
});
