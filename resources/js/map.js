import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import 'leaflet-draw/dist/leaflet.draw.css';
import 'leaflet-draw';

// Фикс иконок Leaflet в webpack
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

const map = L.map('map').setView([61.785, 34.346], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

const drawnItems = new L.FeatureGroup().addTo(map);

const drawControl = new L.Control.Draw({
    edit: { featureGroup: drawnItems },
    draw: { polyline: true, polygon: true, marker: true, circle: false, rectangle: false }
});

map.addControl(drawControl);

map.on(L.Draw.Event.CREATED, (e) => {
    drawnItems.addLayer(e.layer);
    console.log(drawnItems.toGeoJSON());
});

console.log('map init', map);
