/**
 * Alpine component for the contact-page office map. Renders a single
 * MapLibre GL JS pin at the configured office coordinates.
 *
 * Mounted via:
 *
 *   <div x-data="officeMap" data-coords='[lon, lat]'>
 *     <div x-ref="canvas"></div>
 *   </div>
 *
 * MapLibre is loaded from unpkg via the `<script>` and `<link>` tags
 * in the site layout, so `window.maplibregl` is normally on the page
 * before Alpine boots — we still wait for it to cover the deferred edge.
 */

function whenMapLibreReady() {
    if (window.maplibregl) return Promise.resolve(window.maplibregl);
    return new Promise((resolve, reject) => {
        const start = Date.now();
        const tick = () => {
            if (window.maplibregl) return resolve(window.maplibregl);
            if (Date.now() - start > 10000) return reject(new Error('MapLibre never loaded'));
            requestAnimationFrame(tick);
        };
        tick();
    });
}

export default function officeMap() {
    return {
        map: null,

        async init() {
            const coords = this._parseCoords(this.$el.dataset.coords);
            if (!coords) return;

            const maplibregl = await whenMapLibreReady();

            this.map = new maplibregl.Map({
                container: this.$refs.canvas,
                style: '/maps/style.json',
                center: coords,
                zoom: 15,
                attributionControl: { compact: true },
                interactive: true,
            });

            this.map.addControl(
                new maplibregl.NavigationControl({ showCompass: false, showZoom: true }),
                'top-right'
            );

            this.map.on('load', () => {
                this.map.addSource('office-point', {
                    type: 'geojson',
                    data: {
                        type: 'Feature',
                        geometry: { type: 'Point', coordinates: coords },
                    },
                });
                this.map.addLayer({
                    id: 'office-circle',
                    type: 'circle',
                    source: 'office-point',
                    paint: {
                        'circle-radius': 9,
                        'circle-color': '#bb4d00',
                        'circle-stroke-color': '#ffffff',
                        'circle-stroke-width': 3,
                    },
                });
            });
        },

        destroy() {
            if (this.map) {
                this.map.remove();
                this.map = null;
            }
        },

        _parseCoords(raw) {
            if (!raw) return null;
            try {
                const parsed = JSON.parse(raw);
                if (!Array.isArray(parsed) || parsed.length !== 2) return null;
                const [lon, lat] = parsed.map(Number);
                if (Number.isNaN(lon) || Number.isNaN(lat)) return null;
                return [lon, lat];
            } catch {
                return null;
            }
        },
    };
}
