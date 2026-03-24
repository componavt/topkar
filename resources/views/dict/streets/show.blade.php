@extends('layouts.page')

@section('headTitle', $street->name)
@section('header', trans('navigation.streets'))

@section('headExtra')
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        .street-map-box {
            margin-top: 24px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
        }

        .street-map-head {
            padding: 14px 16px;
            border-bottom: 1px solid #e5e7eb;
        }

        .street-map-title {
            margin: 0 0 6px;
            font-size: 18px;
            font-weight: 700;
        }

        .street-map-status {
            font-size: 14px;
            color: #475569;
        }

        .street-map-status.error {
            color: #b91c1c;
        }

        .street-map-status.success {
            color: #15803d;
        }

        #streetMap {
            width: 100%;
            height: 560px;
            background: #f8fafc;
        }
    </style>
@endsection

@section('page_top')
    <h2>{{ $street->name }}</h2>
    <p><span class="important">TopKar ID: {{ $street->id }}</span></p>
@endsection

@section('top_links')
    {!! to_list('street', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_edit('street', $street, $args_by_get) !!}
        {!! to_delete('street', $street, $args_by_get) !!}
        {!! to_create('street', $args_by_get, trans('messages.create_new_f')) !!}
    @endif
    {!! to_link(trans('navigation.history'), route('streets.history', $street), $args_by_get, 'top-icon to-history') !!}
@endsection

@section('content')
    @if (optional($street->geotype)->name || user_can_edit())
    <p><span class='field-name'>{{trans('misc.type')}}</span>:
    <span class='field-value'>{{ optional($street->geotype)->name }}</span></p>
    @endif

    @if (optional($street)->name_ru || user_can_edit())
    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_russian')}}</span>:
    <span class='field-value'>{{ optional($street)->name_ru }}</span></p>
    @endif

    @if (optional($street)->name_krl || user_can_edit())
    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_karelian')}}</span>:
    <span class='field-value'>{{ optional($street)->name_krl }}</span></p>
    @endif

    @if (optional($street)->name_fi || user_can_edit())
    <p><span class='field-name'>{{trans('toponym.name')}} {{trans('messages.in_finnish')}}</span>:
    <span class='field-value'>{{ optional($street)->name_fi }}</span></p>
    @endif

    @if (optional($street)->history || user_can_edit())
    <p><span class='field-name'>{{trans('toponym.history')}}</span>:
    <span class='field-value'>{!! nl2br(e(optional($street)->history)) !!}</span></p>
    @endif

    @if (optional($street)->history || user_can_edit())
    <p><span class='field-name'>{{trans('toponym.main_info')}}</span>:
    <span class='field-value'>{!! nl2br(e(optional($street)->main_info)) !!}</span></p>
    @endif

    @if (sizeof($street->structs) || user_can_edit())
        <p><span class='field-name'>{{trans('misc.struct')}}</span></p>
        <ol>
        @foreach ($street->structs as $struct)
        <li>
            <span class='field-value'>{{ optional($struct)->name }}</span>
            ({{ $struct && $struct->structhier ? $struct->structhier->parent->name. ' '. mb_strtolower($struct->structhier->name) : '' }})
        </li>
        @endforeach
        </ol>
    @endif

    @if (optional($street)->name_ru || user_can_edit())
        <div class="street-map-box">
            <div class="street-map-head">
                <h3 class="street-map-title">Улица на карте</h3>
                <div id="streetMapStatus" class="street-map-status">
                    Загружаю геометрию...
                </div>
            </div>
            <div id="streetMap"></div>
        </div>
    @endif
@endsection

@section('footScriptExtra')
    {!! Html::script('js/rec-delete-link.js') !!}
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    @if (!empty($street->name_ru))
    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            const statusEl = document.getElementById('streetMapStatus');
            const map = L.map('streetMap').setView([61.7849, 34.3469], 12);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const url = @json(route('streets.geometry.local', $street));
            const streetName = @json($street->name_ru);

            function setStatus(text, type = '') {
                statusEl.textContent = text;
                statusEl.className = 'street-map-status' + (type ? ' ' + type : '');
            }

            try {
                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }

                const geojson = await response.json();

                if (!geojson.features || !geojson.features.length) {
                    setStatus('Локальная геометрия для этой улицы не загружена.');
                    return;
                }

                const layer = L.geoJSON(geojson, {
                    style: {
                        color: '#2563eb',
                        weight: 5,
                        opacity: 0.95,
                        lineCap: 'round',
                        lineJoin: 'round'
                    },
                    onEachFeature: function (feature, leafletLayer) {
                        leafletLayer.bindPopup(
                            '<strong>' + (feature.properties?.name || streetName) + '</strong>'
                        );
                    }
                }).addTo(map);

                const bounds = layer.getBounds();
                if (bounds.isValid()) {
                    map.fitBounds(bounds.pad(0.15), { maxZoom: 17 });
                }

                setStatus('Геометрия загружена.', 'success');
                setTimeout(() => map.invalidateSize(), 100);
            } catch (error) {
                console.error(error);
                setStatus('Не удалось загрузить локальную геометрию.', 'error');
            }
        });
    </script>
    @endif
@endsection

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
@endsection
