export default function heroCarousel({ count = 0, interval = 6000 } = {}) {
    return {
        count,
        interval,
        current: 0,
        progress: 0,        // 0 → 1, advances over `interval` and resets each slide
        startedAt: 0,
        rafId: null,

        init() {
            if (this.count <= 1) return;
            this.startCycle();
        },

        startCycle() {
            this.progress = 0;
            this.startedAt = performance.now();
            this.tick();
        },

        tick() {
            const elapsed = performance.now() - this.startedAt;
            this.progress = Math.min(1, elapsed / this.interval);

            if (elapsed >= this.interval) {
                this.advance();
                return;
            }
            this.rafId = requestAnimationFrame(() => this.tick());
        },

        advance() {
            this.current = (this.current + 1) % this.count;
            this.startCycle();
        },

        goTo(i) {
            this.current = (i + this.count) % this.count;
            cancelAnimationFrame(this.rafId);
            if (this.count > 1) this.startCycle();
        },
        next() { this.goTo(this.current + 1); },
        prev() { this.goTo(this.current - 1); },
    };
}
