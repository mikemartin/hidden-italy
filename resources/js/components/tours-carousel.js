import Splide from '@splidejs/splide';

export default function toursCarousel({ count = 0 } = {}) {
    return {
        count,
        progress: 0,
        splide: null,

        init() {
            if (this.count === 0) return;

            this.splide = new Splide(this.$refs.splide, {
                perPage: 3,
                perMove: 1,
                gap: '2.25rem',
                pagination: false,
                arrows: false,
                breakpoints: {
                    768: {
                        perPage: 1,
                        gap: '1.5rem',
                    },
                },
            });

            this.splide.on('mounted move', () => {
                const end = this.splide.Components.Controller.getEnd() + 1;
                this.progress = Math.min((this.splide.index + 1) / end, 1) * 100;
            });

            this.splide.mount();
        },

        next() {
            this.splide?.go('+');
        },

        prev() {
            this.splide?.go('-');
        },

        destroy() {
            this.splide?.destroy();
            this.splide = null;
        },
    };
}
