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
 *
 * Following the canonical Alpine integration pattern (see chartjs /
 * apexcharts examples in the Alpine docs), the MapLibre Map instance
 * and ResizeObserver live in closure-scoped `let` bindings — not on
 * the reactive x-data object. That keeps Alpine from wrapping a Map /
 * Observer in a Proxy, which can interfere with internal `this`
 * references inside third-party classes.
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

/**
 * Resolves once `el` reports non-zero width AND height. Returns
 * immediately if already sized, otherwise watches via ResizeObserver
 * until the first non-zero measurement. The map mounts inside a
 * tabpanel whose `md:hidden` class flips after Alpine's `$nextTick` —
 * racing this component's init — and pages with `x-reveal` ancestors
 * may briefly hold zero height during boot.
 */
function whenSized(el) {
    if (el.clientWidth > 0 && el.clientHeight > 0) {
        return Promise.resolve();
    }
    return new Promise((resolve) => {
        const observer = new ResizeObserver((entries) => {
            for (const entry of entries) {
                const { width, height } = entry.contentRect;
                if (width > 0 && height > 0) {
                    observer.disconnect();
                    resolve();
                    return;
                }
            }
        });
        observer.observe(el);
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

function drawRoute(map, maplibregl, start, end, region) {
    // Dashed line between start and end.
    map.addSource('route', {
        type: 'geojson',
        data: {
            type: 'Feature',
            geometry: { type: 'LineString', coordinates: [start, end] },
        },
    });
    map.addLayer({
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

    // Start (green) and end (terracotta) point markers. Each marker is
    // a coloured disc with a white halo and a small white pip on top,
    // so the pin reads as a traditional concentric map marker rather
    // than a flat coloured dot.
    for (const [id, coords, color] of [
        ['start', start, '#6D998C'], // sage green
        ['end', end, '#bb4d00'], // terracotta
    ]) {
        map.addSource(`${id}-point`, {
            type: 'geojson',
            data: {
                type: 'Feature',
                geometry: { type: 'Point', coordinates: coords },
            },
        });
        map.addLayer({
            id: `${id}-circle`,
            type: 'circle',
            source: `${id}-point`,
            paint: {
                'circle-radius': 12,
                'circle-color': color,
                'circle-stroke-color': '#ffffff',
                'circle-stroke-width': 2,
            },
        });
        map.addLayer({
            id: `${id}-circle-inner`,
            type: 'circle',
            source: `${id}-point`,
            paint: {
                'circle-radius': 4,
                'circle-color': '#ffffff',
            },
        });
    }

    // Fit bounds — include the regional anchor city so the map zooms
    // wide enough to give context.
    const bounds = new maplibregl.LngLatBounds().extend(start).extend(end);
    const anchor = REGION_ANCHOR[region];
    if (anchor) bounds.extend(anchor.coords);
    map.fitBounds(bounds, { padding: 40, maxZoom: 8.5, duration: 0 });
}

export default function tourMap() {
    let map = null;
    let resizeObserver = null;

    return {
        async init() {
            const start = parseCoords(this.$el.dataset.start);
            const end = parseCoords(this.$el.dataset.end);
            const region = (this.$el.dataset.region || '').trim();

            if (!start || !end) return;

            const maplibregl = await whenMapLibreReady();
            const canvas = this.$refs.canvas;

            // MapLibre needs a sized container to measure tiles. Park
            // construction until the canvas reports real dimensions —
            // covers the tabpanel `md:hidden` race, reveal animations,
            // and any layout reflow during boot.
            await whenSized(canvas);

            map = new maplibregl.Map({
                container: canvas,
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
            map.addControl(
                new maplibregl.NavigationControl({ showCompass: false, showZoom: true }),
                'top-right'
            );

            map.on('load', () => drawRoute(map, maplibregl, start, end, region));

            // Keep the canvas measured. Fires when the user toggles tabs
            // (display:none ↔ visible), when the viewport resizes, when
            // a reveal animation completes, or when content above the
            // map reflows. resize() preserves the user's pan/zoom — we
            // deliberately do NOT re-fit bounds here so a visitor's view
            // survives tab toggles.
            resizeObserver = new ResizeObserver(() => {
                if (!map) return;
                if (canvas.clientWidth === 0 || canvas.clientHeight === 0) return;
                map.resize();
            });
            resizeObserver.observe(canvas);
        },

        destroy() {
            resizeObserver?.disconnect();
            resizeObserver = null;
            map?.remove();
            map = null;
        },
    };
}
