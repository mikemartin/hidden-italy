import Alpine from 'alpinejs'
import collapse from '@alpinejs/collapse'
import focus from '@alpinejs/focus'
import morph from '@alpinejs/morph'
import persist from '@alpinejs/persist'
import precognition from 'laravel-precognition-alpine';
import simpleLikes from './components/simple-likes.js';

window.Alpine = Alpine
window.simpleLikes = simpleLikes;

Alpine.store('wishlist', {
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

Alpine.plugin([collapse, focus, morph, persist, precognition])
Alpine.data('simpleLikes', simpleLikes);
Alpine.start();
Alpine.store('wishlist').refresh();

// Clear SimpleLikes cache when Livewire updates the DOM
document.addEventListener('livewire:morph', () => {
    if (window.SimpleLikesBatch) {
        window.SimpleLikesBatch.cache = {};
    }
});
