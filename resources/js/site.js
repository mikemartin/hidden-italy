import Alpine from 'alpinejs';
import simpleLikes from './components/simple-likes.js';

window.Alpine = Alpine;
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

// Register Alpine components before starting
Alpine.data('simpleLikes', simpleLikes);

Alpine.start();
Alpine.store('wishlist').refresh();

// Clear SimpleLikes cache when Livewire updates the DOM
document.addEventListener('livewire:morph', () => {
    if (window.SimpleLikesBatch) {
        window.SimpleLikesBatch.cache = {};
    }
});