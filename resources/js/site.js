import Alpine from 'alpinejs';
import './components/simple-likes.js';

window.Alpine = Alpine;

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

Alpine.start();
Alpine.store('wishlist').refresh();