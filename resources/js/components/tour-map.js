/**
 * Alpine component for the tour walk map. Renders a MapLibre GL JS map
 * with start/end markers, a dashed route line between them, and uses a
 * regional anchor city to widen the bounds so the route reads in the
 * context of a recognisable Italian city.
 *
 * Mounted via:
 *
 *   <div
 *     x-data="tourMap"
 *     data-start='[lon, lat]'
 *     data-end='[lon, lat]'
 *     data-region="Piedmont"
 *   >
 *     <div x-ref="canvas"></div>
 *   </div>
 *
 * MapLibre is loaded from unpkg via the `<script>` and `<link>` tags
 * in the site layout (per the official CDN docs), so `window.maplibregl`
 * is already on the page when this component initialises. We just wait
 * for it in case Alpine boots before the deferred script.
 */

/**
 * Resolves once `window.maplibregl` is defined. The MapLibre `<script>`
 * is `defer`ed in the layout, so on most pages it lands before Alpine
 * boots — but we poll briefly to cover the edge case where a map mounts
 * inside a long-running Alpine init.
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

// Regional anchor: each Italian region keyed to its capital city
// (English + Italian-language variants from Nominatim). The anchor is
// added to `fitBounds` so the map zooms wide enough to show the route
// alongside a city the visitor likely recognises.
const REGION_ANCHOR = {
    Piedmont: { coords: [7.6869, 45.0703], name: 'Turin' },
    Piemonte: { coords: [7.6869, 45.0703], name: 'Turin' },
    Lombardy: { coords: [9.19, 45.4642], name: 'Milan' },
    Lombardia: { coords: [9.19, 45.4642], name: 'Milan' },
    Liguria: { coords: [8.9463, 44.4056], name: 'Genoa' },
    Veneto: { coords: [12.3155, 45.4408], name: 'Venice' },
    'Trentino-Alto Adige': { coords: [11.1217, 46.0667], name: 'Trento' },
    'Trentino-Südtirol': { coords: [11.1217, 46.0667], name: 'Trento' },
    'Friuli-Venezia Giulia': { coords: [13.7768, 45.6495], name: 'Trieste' },
    'Emilia-Romagna': { coords: [11.3426, 44.4949], name: 'Bologna' },
    Tuscany: { coords: [11.2558, 43.7696], name: 'Florence' },
    Toscana: { coords: [11.2558, 43.7696], name: 'Florence' },
    Umbria: { coords: [12.3888, 43.1122], name: 'Perugia' },
    Marche: { coords: [13.5189, 43.6158], name: 'Ancona' },
    Lazio: { coords: [12.4964, 41.9028], name: 'Rome' },
    Abruzzo: { coords: [13.7, 42.35], name: "L'Aquila" },
    Molise: { coords: [14.6666, 41.5575], name: 'Campobasso' },
    Campania: { coords: [14.2681, 40.8518], name: 'Naples' },
    Apulia: { coords: [16.8719, 41.1171], name: 'Bari' },
    Puglia: { coords: [16.8719, 41.1171], name: 'Bari' },
    Basilicata: { coords: [15.8056, 40.6395], name: 'Potenza' },
    Calabria: { coords: [16.587, 38.9089], name: 'Catanzaro' },
    Sicily: { coords: [13.3613, 38.1157], name: 'Palermo' },
    Sicilia: { coords: [13.3613, 38.1157], name: 'Palermo' },
    Sardinia: { coords: [9.1191, 39.2238], name: 'Cagliari' },
    Sardegna: { coords: [9.1191, 39.2238], name: 'Cagliari' },
    'Aosta Valley': { coords: [7.3208, 45.7372], name: 'Aosta' },
    "Valle d'Aosta": { coords: [7.3208, 45.7372], name: 'Aosta' },
};

export default function tourMap() {
    return {
        map: null,

        async init() {
            const start = this._parseCoords(this.$el.dataset.start);
            const end = this._parseCoords(this.$el.dataset.end);
            const region = (this.$el.dataset.region || '').trim();

            if (!start || !end) {
                return;
            }

            // The MapLibre `<script>` in the layout attaches
            // `window.maplibregl`. Wait for it in case Alpine init runs
            // before the deferred script lands.
            const maplibregl = await whenMapLibreReady();

            this.map = new maplibregl.Map({
                container: this.$refs.canvas,
                style: '/maps/style.json',
                center: start,
                zoom: 7,
                attributionControl: { compact: true },
                interactive: true,
                // Page scroll passes through unless the user holds Ctrl/Cmd,
                // and touch pan requires two fingers — stops the map from
                // hijacking vertical scroll on long tour pages. MapLibre
                // renders its own hint overlay on accidental gestures.
                cooperativeGestures: true,
            });

            // Basic zoom in / zoom out buttons (top-right). Compass dropped —
            // there's no rotation to reset on a route map.
            this.map.addControl(
                new maplibregl.NavigationControl({ showCompass: false, showZoom: true }),
                'top-right'
            );

            this.map.on('load', () => {
                this._drawRoute(maplibregl, start, end, region);
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

        _drawRoute(maplibregl, start, end, region) {
            // Dashed line between start and end.
            this.map.addSource('route', {
                type: 'geojson',
                data: {
                    type: 'Feature',
                    geometry: { type: 'LineString', coordinates: [start, end] },
                },
            });
            this.map.addLayer({
                id: 'route-line',
                type: 'line',
                source: 'route',
                layout: { 'line-cap': 'round', 'line-join': 'round' },
                paint: {
                    'line-color': '#bb4d00',
                    'line-width': 3,
                    'line-dasharray': [2, 2],
                },
            });

            // Start (green) and end (terracotta) point markers.
            for (const [id, coords, color] of [
                ['start', start, '#6D998C'], // sage green
                ['end', end, '#bb4d00'], // terracotta
            ]) {
                this.map.addSource(`${id}-point`, {
                    type: 'geojson',
                    data: {
                        type: 'Feature',
                        geometry: { type: 'Point', coordinates: coords },
                    },
                });
                this.map.addLayer({
                    id: `${id}-circle`,
                    type: 'circle',
                    source: `${id}-point`,
                    paint: {
                        'circle-radius': 8,
                        'circle-color': color,
                        'circle-stroke-color': '#ffffff',
                        'circle-stroke-width': 3,
                    },
                });
            }

            // Fit bounds — include the regional anchor city so the map
            // zooms wide enough to give context.
            const bounds = new maplibregl.LngLatBounds().extend(start).extend(end);
            const anchor = REGION_ANCHOR[region];
            if (anchor) bounds.extend(anchor.coords);
            this.map.fitBounds(bounds, { padding: 40, maxZoom: 8.5, duration: 0 });
        },
    };
}
