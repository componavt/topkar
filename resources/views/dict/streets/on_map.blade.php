@extends('layouts.page')

@section('headTitle', trans('navigation.streets'))
@section('header', trans('navigation.streets'))

@section('headExtra')
    <meta name="referrer" content="strict-origin-when-cross-origin">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <style>
        .tk-streets-page {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 16px;
            min-height: 70vh;
        }

        .tk-panel {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }

        .tk-panel-section {
            padding: 16px;
            border-bottom: 1px solid #eef2f7;
        }

        .tk-panel-section:last-child {
            border-bottom: 0;
        }

        .tk-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 13px;
            color: #475569;
        }

        .tk-search-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 8px;
        }

        .tk-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }

        .tk-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,.15);
        }

        .tk-btn {
            padding: 10px 14px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .tk-btn[disabled] {
            opacity: .65;
            cursor: wait;
        }

        .tk-status {
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 14px;
            line-height: 1.45;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .tk-status.warning {
            background: #fffbeb;
            color: #b45309;
            border-color: #fcd34d;
        }

        .tk-status.error {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        .tk-status.success {
            background: #f0fdf4;
            color: #15803d;
            border-color: #bbf7d0;
        }

        .tk-note {
            margin-top: 10px;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            color: #475569;
            font-size: 13px;
            line-height: 1.45;
        }

        .tk-results-title {
            margin: 0 0 12px;
            font-size: 16px;
            font-weight: 700;
        }

        .tk-results {
            display: grid;
            gap: 10px;
            max-height: 460px;
            overflow: auto;
        }

        .tk-result {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px;
            background: #fff;
            cursor: pointer;
        }

        .tk-result.active {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .tk-result-top {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
            align-items: center;
        }

        .tk-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .tk-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        .tk-result-text {
            margin: 0 0 10px;
            font-size: 13px;
            color: #334155;
            line-height: 1.45;
        }

        .tk-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .tk-btn-ghost {
            padding: 7px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            color: #334155;
            font-size: 13px;
            cursor: pointer;
        }

        .tk-map-wrap {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            min-height: 700px;
        }

        #streetMap {
            width: 100%;
            height: 700px;
        }

        .leaflet-popup-content {
            font-size: 13px;
            line-height: 1.45;
        }

        @media (max-width: 992px) {
            .tk-streets-page {
                grid-template-columns: 1fr;
            }

            #streetMap,
            .tk-map-wrap {
                min-height: 60vh;
                height: 60vh;
            }
        }
    </style>
@endsection

@section('page_top')
@endsection

@section('top_links')
    {!! to_list('street', $args_by_get) !!}
@endsection

@section('content')
    <div class="tk-streets-page">
        <div class="tk-panel">
            <div class="tk-panel-section">
                <label class="tk-label" for="streetInput">Название улицы</label>

                <div class="tk-search-row">
                    <input
                        id="streetInput"
                        class="tk-input"
                        type="text"
                        placeholder="Например: Ленина"
                        autocomplete="off"
                    >
                    <button id="searchBtn" class="tk-btn" type="button">Найти</button>
                </div>

                <div id="searchStatus" class="tk-status" style="margin-top: 12px;">
                    Готово к поиску. Приблизьте карту к нужному району и введите название улицы.
                </div>

                <div id="bboxInfo" class="tk-note">
                    Область поиска будет рассчитана после инициализации карты.
                </div>

                <div class="tk-note">
                    Поиск ограничен текущим bbox карты, чтобы не перегружать Overpass и не получать улицы с одинаковым названием из других районов.
                </div>
            </div>

            <div class="tk-panel-section">
                <h3 class="tk-results-title">Найденные варианты: <span id="resultCount">0</span></h3>
                <div id="resultsList" class="tk-results"></div>
            </div>
        </div>

        <div class="tk-map-wrap">
            <div id="streetMap"></div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        (function () {
            const DEFAULT_CENTER = [61.7849, 34.3469];
            const DEFAULT_ZOOM = 13;

            const OVERPASS_ENDPOINTS = [
                'https://overpass-api.de/api/interpreter',
                'https://lz4.overpass-api.de/api/interpreter',
                'https://overpass.kumi.systems/api/interpreter'
            ];

            const VARIANT_COLORS = ['#ef4444', '#f97316', '#8b5cf6', '#14b8a6', '#22c55e', '#eab308'];
            const ACTIVE_COLOR = '#2563eb';
            const SEARCH_DEBOUNCE_MS = 700;
            const REQUEST_TIMEOUT_MS = 45000; // клиентский таймаут 45 сек.

            const streetInput = document.getElementById('streetInput');
            const searchBtn = document.getElementById('searchBtn');
            const searchStatus = document.getElementById('searchStatus');
            const bboxInfo = document.getElementById('bboxInfo');
            const resultsList = document.getElementById('resultsList');
            const resultCount = document.getElementById('resultCount');

            const map = L.map('streetMap').setView(DEFAULT_CENTER, DEFAULT_ZOOM);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            setTimeout(() => map.invalidateSize(), 100);

            const resultLayerGroup = L.layerGroup().addTo(map);

            let variantLayers = [];
            let activeVariantIndex = -1;
            let searchToken = 0;
            let debounceTimer = null;

            function setStatus(message, type = 'info') {
                searchStatus.textContent = message;
                searchStatus.className = 'tk-status';
                if (type === 'warning') searchStatus.classList.add('warning');
                if (type === 'error') searchStatus.classList.add('error');
                if (type === 'success') searchStatus.classList.add('success');
            }

            function setLoading(flag) {
                searchBtn.disabled = flag;
                searchBtn.textContent = flag ? 'Поиск...' : 'Найти';
            }

            function clearResults() {
                resultLayerGroup.clearLayers();
                resultsList.innerHTML = '';
                resultCount.textContent = '0';
                variantLayers = [];
                activeVariantIndex = -1;
            }

            function getSearchBounds() {
                const b = map.getBounds().pad(0.15);
                return {
                    south: b.getSouth(),
                    west: b.getWest(),
                    north: b.getNorth(),
                    east: b.getEast()
                };
            }

            function updateBboxInfo() {
                const b = getSearchBounds();
                bboxInfo.textContent =
                    'Текущая область поиска (bbox): ' +
                    b.south.toFixed(5) + ', ' + b.west.toFixed(5) + ', ' +
                    b.north.toFixed(5) + ', ' + b.east.toFixed(5) +
                    '. Масштаб: z' + map.getZoom() + '.';
            }

            function isAreaTooLarge(bounds) {
                const latSpan = Math.abs(bounds.north - bounds.south);
                const lonSpan = Math.abs(bounds.east - bounds.west);

                return map.getZoom() < 13 || latSpan > 0.35 || lonSpan > 0.55;
            }

            function escapeRegex(value) {
                return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }

            function buildOverpassQuery(streetName, bounds) {
                const safe = escapeRegex(streetName.trim());

                return `
            [out:json][timeout:12];
            (
            way["highway"]["name"~"${safe}",i](${bounds.south},${bounds.west},${bounds.north},${bounds.east});
            );
            out geom;
                `.trim();
            }

            async function sleep(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            }

            async function fetchOverpass(query) {
                let lastError = null;

                for (const endpoint of OVERPASS_ENDPOINTS) {
                    for (let attempt = 1; attempt <= 2; attempt++) {
                        const controller = new AbortController();
                        const timeoutId = setTimeout(() => controller.abort(), REQUEST_TIMEOUT_MS);

                        try {
                            const response = await fetch(endpoint, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                                },
                                body: 'data=' + encodeURIComponent(query),
                                signal: controller.signal
                            });

                            clearTimeout(timeoutId);

                            if (!response.ok) {
                                throw new Error('HTTP ' + response.status);
                            }

                            return await response.json();
                        } catch (error) {
                            clearTimeout(timeoutId);
                            lastError = error;

                            if (attempt < 2) {
                                await sleep(1200);
                            }
                        }
                    }
                }

                throw lastError || new Error('Все Overpass endpoint недоступны');
            }

            function overpassToGeoJSON(osmJson) {
                const features = (osmJson.elements || [])
                    .filter(el => el.type === 'way' && Array.isArray(el.geom) && el.geom.length >= 2)
                    .map(el => ({
                        type: 'Feature',
                        properties: {
                            osm_id: el.id,
                            name: el.tags?.name || '',
                            highway: el.tags?.highway || '',
                            tags: el.tags || {}
                        },
                        geometry: {
                            type: 'LineString',
                            coordinates: el.geom.map(p => [p.lon, p.lat])
                        }
                    }));

                return {
                    type: 'FeatureCollection',
                    features
                };
            }

            function computeBounds(features) {
                const latLngs = [];

                features.forEach(feature => {
                    if (!feature.geometry) return;
                    if (feature.geometry.type !== 'LineString') return;

                    feature.geometry.coordinates.forEach(([lng, lat]) => {
                        latLngs.push([lat, lng]);
                    });
                });

                return latLngs.length ? L.latLngBounds(latLngs) : null;
            }

            function computeLengthMeters(feature) {
                if (!feature.geometry || feature.geometry.type !== 'LineString') return 0;

                let total = 0;
                const coords = feature.geometry.coordinates;

                for (let i = 1; i < coords.length; i++) {
                    const [lng1, lat1] = coords[i - 1];
                    const [lng2, lat2] = coords[i];
                    total += map.distance([lat1, lng1], [lat2, lng2]);
                }

                return total;
            }

            function formatLength(meters) {
                return meters >= 1000
                    ? (meters / 1000).toFixed(2) + ' км'
                    : Math.round(meters) + ' м';
            }

            function endpointKeys(feature, precision = 5) {
                const keys = new Set();
                if (!feature.geometry || feature.geometry.type !== 'LineString') return keys;

                const line = feature.geometry.coordinates;
                if (!line.length) return keys;

                const first = line[0];
                const last = line[line.length - 1];

                keys.add(first[0].toFixed(precision) + ',' + first[1].toFixed(precision));
                keys.add(last[0].toFixed(precision) + ',' + last[1].toFixed(precision));

                return keys;
            }

            function groupVariants(featureCollection) {
                const items = featureCollection.features.map((feature, index) => ({
                    index,
                    feature,
                    keys: endpointKeys(feature)
                }));

                const byIndex = new Map(items.map(item => [item.index, item]));
                const keyMap = new Map();

                items.forEach(item => {
                    item.keys.forEach(key => {
                        if (!keyMap.has(key)) keyMap.set(key, []);
                        keyMap.get(key).push(item.index);
                    });
                });

                const visited = new Set();
                const groups = [];

                items.forEach(item => {
                    if (visited.has(item.index)) return;

                    const stack = [item.index];
                    const features = [];
                    visited.add(item.index);

                    while (stack.length) {
                        const currentIndex = stack.pop();
                        const current = byIndex.get(currentIndex);
                        features.push(current.feature);

                        current.keys.forEach(key => {
                            (keyMap.get(key) || []).forEach(neighbor => {
                                if (!visited.has(neighbor)) {
                                    visited.add(neighbor);
                                    stack.push(neighbor);
                                }
                            });
                        });
                    }

                    groups.push(features);
                });

                return groups
                    .sort((a, b) => b.length - a.length)
                    .map((features, idx) => ({
                        id: idx,
                        features,
                        bounds: computeBounds(features),
                        length: features.reduce((sum, feature) => sum + computeLengthMeters(feature), 0)
                    }));
            }

            function setLayerStyle(layer, color, active) {
                layer.setStyle({
                    color: active ? ACTIVE_COLOR : color,
                    weight: active ? 6 : 5,
                    opacity: 0.95,
                    lineCap: 'round',
                    lineJoin: 'round'
                });
            }

            function selectVariant(index, fitToBounds = true) {
                activeVariantIndex = index;

                variantLayers.forEach((item, i) => {
                    setLayerStyle(item.layer, item.color, i === index);
                });

                Array.from(resultsList.children).forEach((node, i) => {
                    node.classList.toggle('active', i === index);
                });

                const variant = variantLayers[index];
                if (fitToBounds && variant && variant.bounds && variant.bounds.isValid()) {
                    map.fitBounds(variant.bounds.pad(0.2), {maxZoom: 17});
                }
            }

            function renderVariants(streetName, variants) {
                clearResults();

                if (!variants.length) {
                    setStatus('Ничего не найдено. Попробуйте приблизить карту или уточнить название.', 'warning');
                    return;
                }

                resultCount.textContent = String(variants.length);

                variants.forEach((variant, index) => {
                    const color = VARIANT_COLORS[index % VARIANT_COLORS.length];
                    const featureCollection = {
                        type: 'FeatureCollection',
                        features: variant.features
                    };

                    const layer = L.geoJSON(featureCollection, {
                        style: {
                            color,
                            weight: 5,
                            opacity: 0.95
                        },
                        onEachFeature: function (feature, leafletLayer) {
                            leafletLayer.bindPopup(
                                '<div>' +
                                '<div><strong>' + (feature.properties.name || streetName) + '</strong></div>' +
                                '<div>OSM way: ' + feature.properties.osm_id + '</div>' +
                                '<div>Тип: ' + (feature.properties.highway || 'unknown') + '</div>' +
                                '</div>'
                            );

                            leafletLayer.on('click', function () {
                                selectVariant(index, false);
                            });
                        }
                    }).addTo(resultLayerGroup);

                    variantLayers.push({
                        layer,
                        bounds: variant.bounds,
                        color
                    });

                    const item = document.createElement('div');
                    item.className = 'tk-result';
                    item.innerHTML = `
                        <div class="tk-result-top">
                            <span class="tk-badge">
                                <span class="tk-dot" style="background:${color}"></span>
                                Вариант ${index + 1}
                            </span>
                            <span>${variant.features.length} сегм.</span>
                        </div>
                        <p class="tk-result-text">
                            Улица: <strong>${streetName}</strong><br>
                            Протяжённость: ${formatLength(variant.length)}
                        </p>
                        <div class="tk-actions">
                            <button type="button" class="tk-btn-ghost" data-role="show">Показать</button>
                            <button type="button" class="tk-btn-ghost" data-role="select">Выбрать</button>
                        </div>
                    `;

                    item.querySelector('[data-role="show"]').addEventListener('click', function (e) {
                        e.stopPropagation();
                        selectVariant(index, true);
                    });

                    item.querySelector('[data-role="select"]').addEventListener('click', function (e) {
                        e.stopPropagation();
                        selectVariant(index, false);
                    });

                    item.addEventListener('click', function () {
                        selectVariant(index, false);
                    });

                    resultsList.appendChild(item);
                });

                const allBounds = L.featureGroup(variantLayers.map(v => v.layer)).getBounds();
                if (allBounds.isValid()) {
                    map.fitBounds(allBounds.pad(0.1), {maxZoom: 16});
                }

                selectVariant(0, false);
                setStatus('Найдено вариантов: ' + variants.length + '. Все линии показаны на карте.', 'success');
            }

            async function searchStreet(rawValue) {
                const streetName = rawValue.trim();
                const currentToken = ++searchToken;

                if (!streetName) {
                    clearResults();
                    setStatus('Введите название улицы.', 'warning');
                    return;
                }

                const bounds = getSearchBounds();

                if (isAreaTooLarge(bounds)) {
                    clearResults();
                    setStatus('Область слишком большая. Приблизьте карту хотя бы до района.', 'warning');
                    return;
                }

                setLoading(true);
                setStatus('Ищу улицу "' + streetName + '"...');

                try {
                    const query = buildOverpassQuery(streetName, bounds);
                    const osmJson = await fetchOverpass(query);

                    if (currentToken !== searchToken) return;

                    const geojson = overpassToGeoJSON(osmJson);
                    const variants = groupVariants(geojson);

                    renderVariants(streetName, variants);
                } catch (error) {
                    if (currentToken !== searchToken) return;
                    clearResults();

                    const text = error && error.name === 'AbortError'
                        ? 'Overpass не ответил вовремя. Попробуйте ещё раз.'
                        : 'Не удалось получить данные от Overpass.';

                    setStatus(text, 'error');
                    console.error(error);
                } finally {
                    if (currentToken === searchToken) {
                        setLoading(false);
                    }
                }
            }

            function debounceSearch() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const value = streetInput.value.trim();
                    if (value.length >= 2) {
                        searchStreet(value);
                    }
                }, SEARCH_DEBOUNCE_MS);
            }

            searchBtn.addEventListener('click', function () {
                searchStreet(streetInput.value);
            });

            streetInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchStreet(streetInput.value);
                }
            });

            streetInput.addEventListener('input', debounceSearch);

            map.on('moveend zoomend', updateBboxInfo);
            updateBboxInfo();
        })();
    </script>
@stop
