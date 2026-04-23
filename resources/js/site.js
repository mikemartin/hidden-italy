import simpleLikes from './components/simple-likes.js';

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

    window.Alpine.data('simpleLikes', simpleLikes);
});

document.addEventListener('alpine:initialized', () => {
    window.Alpine.store('wishlist').refresh();
});

document.addEventListener('livewire:morph', () => {
    if (window.SimpleLikesBatch) {
        window.SimpleLikesBatch.cache = {};
    }
});
