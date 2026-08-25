<script setup lang="ts">
import { ref, watch, onMounted, onBeforeUnmount, nextTick } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const props = defineProps<{
    utmHuso?: string | number;
    utmBanda?: string;
    utmXEste?: string | number;
    utmYNorte?: string | number;
}>();

const emit = defineEmits<{
    (e: 'update:coordinates', value: { utm_huso: number; utm_banda: string; utm_x_este: number; utm_y_norte: number }): void;
}>();

// Fix Leaflet default icon paths for Vite/Webpack bundlers
delete (L.Icon.Default.prototype as any)._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
});

const mapContainer = ref<HTMLDivElement | null>(null);
let map: L.Map | null = null;
let marker: L.Marker | null = null;

const latDisplay = ref('');
const lngDisplay = ref('');

// ── Conversión Lat/Lng → UTM ──────────────────────────────────────────────
function latLngToUtm(lat: number, lng: number) {
    const a = 6378137;
    const f = 1 / 298.257223563;
    const e = Math.sqrt(2 * f - f * f);
    const e2 = e * e / (1 - e * e);
    const k0 = 0.9996;

    const zoneNumber = Math.floor((lng + 180) / 6) + 1;
    const lng0 = ((zoneNumber - 1) * 6 - 180 + 3) * Math.PI / 180;

    const latRad = lat * Math.PI / 180;
    const lngRad = lng * Math.PI / 180;

    const N = a / Math.sqrt(1 - e * e * Math.sin(latRad) * Math.sin(latRad));
    const T = Math.tan(latRad) * Math.tan(latRad);
    const C = e2 * Math.cos(latRad) * Math.cos(latRad);
    const A = Math.cos(latRad) * (lngRad - lng0);

    const M = a * (
        (1 - e * e / 4 - 3 * e * e * e * e / 64 - 5 * Math.pow(e, 6) / 256) * latRad
        - (3 * e * e / 8 + 3 * e * e * e * e / 32 + 45 * Math.pow(e, 6) / 1024) * Math.sin(2 * latRad)
        + (15 * e * e * e * e / 256 + 45 * Math.pow(e, 6) / 1024) * Math.sin(4 * latRad)
        - (35 * Math.pow(e, 6) / 3072) * Math.sin(6 * latRad)
    );

    let easting = k0 * N * (
        A + (1 - T + C) * A * A * A / 6
        + (5 - 18 * T + T * T + 72 * C - 58 * e2) * Math.pow(A, 5) / 120
    ) + 500000;

    let northing = k0 * (
        M + N * Math.tan(latRad) * (
            A * A / 2
            + (5 - T + 9 * C + 4 * C * C) * Math.pow(A, 4) / 24
            + (61 - 58 * T + T * T + 600 * C - 330 * e2) * Math.pow(A, 6) / 720
        )
    );

    if (lat < 0) {
        northing += 10000000;
    }

    // Banda UTM
    const bandLetters = 'CDEFGHJKLMNPQRSTUVWX';
    let bandIndex = Math.floor((lat + 80) / 8);
    if (bandIndex < 0) bandIndex = 0;
    if (bandIndex > 19) bandIndex = 19;
    const banda = bandLetters[bandIndex];

    return {
        utm_huso: zoneNumber,
        utm_banda: banda,
        utm_x_este: Math.round(easting * 100) / 100,
        utm_y_norte: Math.round(northing * 100) / 100,
    };
}

// ── Conversión UTM → Lat/Lng (para centrar el mapa en coordenadas existentes) ──
function utmToLatLng(huso: number, banda: string, este: number, norte: number): [number, number] | null {
    try {
        const a = 6378137;
        const f = 1 / 298.257223563;
        const e = Math.sqrt(2 * f - f * f);
        const e1 = (1 - Math.sqrt(1 - e * e)) / (1 + Math.sqrt(1 - e * e));
        const k0 = 0.9996;

        const isNorthern = banda >= 'N';
        const x = este - 500000;
        const y = isNorthern ? norte : norte - 10000000;

        const lng0 = ((huso - 1) * 6 - 180 + 3) * Math.PI / 180;

        const M = y / k0;
        const mu = M / (a * (1 - e * e / 4 - 3 * Math.pow(e, 4) / 64 - 5 * Math.pow(e, 6) / 256));

        const phi1 = mu
            + (3 * e1 / 2 - 27 * Math.pow(e1, 3) / 32) * Math.sin(2 * mu)
            + (21 * e1 * e1 / 16 - 55 * Math.pow(e1, 4) / 32) * Math.sin(4 * mu)
            + (151 * Math.pow(e1, 3) / 96) * Math.sin(6 * mu)
            + (1097 * Math.pow(e1, 4) / 512) * Math.sin(8 * mu);

        const e2 = e * e / (1 - e * e);
        const N1 = a / Math.sqrt(1 - e * e * Math.sin(phi1) * Math.sin(phi1));
        const T1 = Math.tan(phi1) * Math.tan(phi1);
        const C1 = e2 * Math.cos(phi1) * Math.cos(phi1);
        const R1 = a * (1 - e * e) / Math.pow(1 - e * e * Math.sin(phi1) * Math.sin(phi1), 1.5);
        const D = x / (N1 * k0);

        const lat = phi1 - (N1 * Math.tan(phi1) / R1) * (
            D * D / 2
            - (5 + 3 * T1 + 10 * C1 - 4 * C1 * C1 - 9 * e2) * Math.pow(D, 4) / 24
            + (61 + 90 * T1 + 298 * C1 + 45 * T1 * T1 - 252 * e2 - 3 * C1 * C1) * Math.pow(D, 6) / 720
        );

        const lng = lng0 + (
            D - (1 + 2 * T1 + C1) * Math.pow(D, 3) / 6
            + (5 - 2 * C1 + 28 * T1 - 3 * C1 * C1 + 8 * e2 + 24 * T1 * T1) * Math.pow(D, 5) / 120
        ) / Math.cos(phi1);

        return [lat * 180 / Math.PI, lng * 180 / Math.PI];
    } catch {
        return null;
    }
}

function onMapClick(e: L.LeafletMouseEvent) {
    const { lat, lng } = e.latlng;

    latDisplay.value = lat.toFixed(6);
    lngDisplay.value = lng.toFixed(6);

    if (marker) {
        marker.setLatLng(e.latlng);
    } else if (map) {
        marker = L.marker(e.latlng, { draggable: true }).addTo(map);
        marker.on('dragend', () => {
            if (marker) {
                const pos = marker.getLatLng();
                latDisplay.value = pos.lat.toFixed(6);
                lngDisplay.value = pos.lng.toFixed(6);
                const utm = latLngToUtm(pos.lat, pos.lng);
                emit('update:coordinates', utm);
            }
        });
    }

    const utm = latLngToUtm(lat, lng);
    emit('update:coordinates', utm);
}

function initMap() {
    if (!mapContainer.value) return;

    // Determinar centro inicial
    let center: [number, number] = [-7.1617, -78.5128]; // Cajamarca por defecto
    let zoom = 14;

    // Si hay coordenadas UTM existentes, convertir a lat/lng
    if (props.utmHuso && props.utmBanda && props.utmXEste && props.utmYNorte) {
        const huso = Number(props.utmHuso);
        const este = Number(props.utmXEste);
        const norte = Number(props.utmYNorte);

        if (huso && este && norte) {
            const result = utmToLatLng(huso, String(props.utmBanda), este, norte);
            if (result) {
                center = result;
                zoom = 17;
            }
        }
    }

    map = L.map(mapContainer.value, {
        center,
        zoom,
        zoomControl: true,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    // Si ya había coordenadas, poner marcador
    if (props.utmHuso && props.utmBanda && props.utmXEste && props.utmYNorte) {
        const huso = Number(props.utmHuso);
        const este = Number(props.utmXEste);
        const norte = Number(props.utmYNorte);

        if (huso && este && norte) {
            const result = utmToLatLng(huso, String(props.utmBanda), este, norte);
            if (result) {
                latDisplay.value = result[0].toFixed(6);
                lngDisplay.value = result[1].toFixed(6);
                marker = L.marker(result, { draggable: true }).addTo(map);
                marker.on('dragend', () => {
                    if (marker) {
                        const pos = marker.getLatLng();
                        latDisplay.value = pos.lat.toFixed(6);
                        lngDisplay.value = pos.lng.toFixed(6);
                        const utm = latLngToUtm(pos.lat, pos.lng);
                        emit('update:coordinates', utm);
                    }
                });
            }
        }
    }

    map.on('click', onMapClick);
}

onMounted(() => {
    nextTick(() => {
        initMap();
    });
});

onBeforeUnmount(() => {
    if (map) {
        map.remove();
        map = null;
        marker = null;
    }
});

// Cuando el modal se muestra, leaflet necesita un invalidateSize
watch(() => mapContainer.value, () => {
    nextTick(() => {
        if (map) {
            map.invalidateSize();
        }
    });
});

defineExpose({
    invalidateSize: () => {
        nextTick(() => {
            if (map) map.invalidateSize();
        });
    },
});
</script>

<template>
    <div class="flex h-full flex-col gap-2">
        <div class="flex items-center justify-between">
            <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                Ubicación en Mapa
            </p>
            <div v-if="latDisplay && lngDisplay" class="text-[11px] font-mono text-muted-foreground">
                {{ latDisplay }}, {{ lngDisplay }}
            </div>
        </div>
        <div
            ref="mapContainer"
            class="flex-1 rounded-lg border bg-muted/20 overflow-hidden"
            style="min-height: 350px;"
        />
        <p class="text-[11px] text-muted-foreground text-center">
            Haz clic en el mapa para seleccionar la ubicación. Puedes arrastrar el marcador.
        </p>
    </div>
</template>

<style>
/* Fix leaflet default icon issue with bundlers */
.leaflet-default-icon-path {
    background-image: url('https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png');
}
</style>
