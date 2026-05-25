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
 *
 * Following the canonical Alpine integration pattern (chartjs /
 * apexcharts examples in the Alpine docs), the MapLibre Map instance
 * lives in a closure-scoped `let` binding — not on the reactive x-data
 * object. That keeps Alpine from wrapping a Map in a Proxy, which can
 * interfere with internal `this` references inside the library.
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

function parseCoords(raw) {
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
}

function drawPin(map, coords) {
    map.addSource('office-point', {
        type: 'geojson',
        data: {
            type: 'Feature',
            geometry: { type: 'Point', coordinates: coords },
        },
    });
    // Outer terracotta disc with the existing white halo …
    map.addLayer({
        id: 'office-circle',
        type: 'circle',
        source: 'office-point',
        paint: {
            'circle-radius': 12,
            'circle-color': '#bb4d00',
            'circle-stroke-color': '#ffffff',
            'circle-stroke-width': 2,
        },
    });
    // … and a small white pip on top, so the marker reads as a
    // traditional concentric map pin rather than a flat dot.
    map.addLayer({
        id: 'office-circle-inner',
        type: 'circle',
        source: 'office-point',
        paint: {
            'circle-radius': 4,
            'circle-color': '#ffffff',
        },
    });
}

export default function officeMap() {
    let map = null;

    return {
        async init() {
            const coords = parseCoords(this.$el.dataset.coords);
            if (!coords) return;

            const maplibregl = await whenMapLibreReady();

            map = new maplibregl.Map({
                container: this.$refs.canvas,
                style: '/maps/style.json',
                center: coords,
                zoom: 15,
                attributionControl: { compact: true },
                interactive: true,
                cooperativeGestures: true,
            });

            map.addControl(
                new maplibregl.NavigationControl({ showCompass: false, showZoom: true }),
                'top-right'
            );

            map.on('load', () => drawPin(map, coords));
        },

        destroy() {
            map?.remove();
            map = null;
        },
    };
}
