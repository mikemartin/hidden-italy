export default function toursCarousel({ count = 0 } = {}) {
    return {
        count,
        current: 0,        // index of the leftmost visible card
        _syncing: false,

        goTo(i) {
            const target = (i + this.count) % this.count;
            this._syncing = true;
            const track = this.$refs.track;
            const card = track?.children[target];
            if (card) {
                track.scrollTo({ left: card.offsetLeft, behavior: 'smooth' });
            }
            this.current = target;
            setTimeout(() => (this._syncing = false), 500);
        },
        next() { this.goTo(this.current + 1); },
        prev() { this.goTo(this.current - 1); },

        syncFromScroll() {
            if (this._syncing) return;
            const track = this.$refs.track;
            const first = track?.children[0];
            if (!track || !first) return;
            const i = Math.round(track.scrollLeft / first.offsetWidth);
            if (i !== this.current && i >= 0 && i < this.count) {
                this.current = i;
            }
        },
    };
}
