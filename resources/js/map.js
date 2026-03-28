import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import 'leaflet-draw/dist/leaflet.draw.css';

window.L = L;

import 'leaflet-draw';

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

const mapEl = document.getElementById('map');
const streetId = mapEl.dataset.streetId;

if (!streetId) {
    console.error('streetId не задан!');
}

const map = L.map(mapEl).setView([61.785, 34.346], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

const drawnItems = new L.FeatureGroup().addTo(map);

const drawControl = new L.Control.Draw({
    edit: { featureGroup: drawnItems },
    draw: { polyline: true, polygon: true, marker: true, circle: false, rectangle: false }
});

map.addControl(drawControl);

// Загрузка существующей геометрии
fetch(`/misc/street-geometry/${streetId}`)
    .then(r => r.json())
    .then(data => {
        if (data && data.geojson) {
            const layer = L.geoJSON(JSON.parse(data.geojson));
            layer.eachLayer(l => drawnItems.addLayer(l));
            map.fitBounds(drawnItems.getBounds());
        }
        // если data === null — просто ничего не делаем, карта пустая
    })
    .catch(err => console.error('Ошибка загрузки геометрии:', err));

function saveGeometry() {
    fetch(`/misc/street-geometry/${streetId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ geojson: drawnItems.toGeoJSON() }),
    })
        .then(r => r.json())
        .then(data => console.log('saved:', data))
        .catch(err => console.error('error:', err));
}

map.on('draw:created', function (e) {
    drawnItems.addLayer(e.layer);
    saveGeometry();
});

map.on('draw:edited', function (e) {
    saveGeometry();
});

map.on('draw:deleted', function (e) {
    saveGeometry(); // если нужно сохранять удаление
});
