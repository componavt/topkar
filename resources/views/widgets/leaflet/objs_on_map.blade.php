@if (!empty($objs))
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
       integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
       crossorigin=""></script>
        {!!Html::style('css/leaflet.css')!!}  

    <script>
      // initialize Leaflet
      var map = L.map('mapid').setView({lon:{{empty($lon) ? '33' : $lon}} , lat: {{empty($lat) ? '63.5' : $lat}}}, {{empty($zoom) ? '7' : $zoom}});
      
      @foreach ($objs->groupBy('color') as $color => $tmp)
      var {{ $color }}Icon = L.icon({
        iconUrl: '/images/markers/marker-icon-{{ $color }}.png',
        iconSize: [30, 41]
      });
      @endforeach

      // add the OpenStreetMap tiles
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
      }).addTo(map);

      // show the scale bar on the lower left corner
      L.control.scale().addTo(map);
      
      @if (!empty($bounds))
      var bounds = [
            [{{ $bounds['min_lat'] }}, {{ $bounds['min_lon'] }}], // Юго-западный угол (SW)
            [{{ $bounds['max_lat'] }}, {{ $bounds['max_lon'] }}]  // Северо-восточный угол (NE)
      ];   
      map.fitBounds(bounds);
      @endif

      // show markers on the map
      @foreach ($objs as $obj)
      L.marker({ lon:{{ $obj['lon'] }} , lat: {{ $obj['lat'] }} }, 
               { icon: {{ $obj['color'] }}Icon })
              .bindPopup('{!! $obj["popup"] !!}'
            @if ($obj['color'] != 'blue' && mb_strlen($obj["popup"]) > 300 || !empty($url_args['popup_all'])) 
                ,{
                @if ($obj['color'] != 'blue' && mb_strlen($obj["popup"]) > 300)
                    maxWidth : {{ mb_strlen($obj["popup"]) < 2400 ? 300+round((mb_strlen($obj["popup"])-300)/3) : 1000 }}
                    @if (!empty($url_args['popup_all']))
                    ,
                    @endif
                @endif
                @if (!empty($url_args['popup_all']))
                    autoClose:false
                @endif
                }
            @endif).addTo(map)
            @if (!empty($url_args['popup_all']))
                .openPopup();
            @endif
      @endforeach
      
    var selectingMinCoords = false;
    var selectingMaxCoords = false;
    var $minLat = $('input[name="min_lat"]');
    var $minLon = $('input[name="min_lon"]');
    var $maxLat = $('input[name="max_lat"]');
    var $maxLon = $('input[name="max_lon"]');

    // Меняем курсор и активируем режим выбора
    $('#select-min-coords').on('click', function(e) {
        e.preventDefault();
        selectingMinCoords = true;
        $(map.getContainer()).css('cursor', 'help');
        
    // Скроллим страницу к низу карты
        $('html, body').animate({
            scrollTop: $(map.getContainer()).offset().top + $(map.getContainer()).outerHeight() - $(window).height()
        }, 500);

        // Показываем мягкое уведомление на экране
        if ($('#coord-hint').length === 0) {
            $('<div id="coord-hint">{{ __('toponym.click_to_select_min_coords') }}</div>')
                .css({
                    position: 'fixed',
                    bottom: '20px',
                    left: '50%',
                    transform: 'translateX(-50%)',
                    background: '#333',
                    color: '#fff',
                    padding: '10px 20px',
                    borderRadius: '8px',
                    zIndex: 10000,
                    fontSize: '14px'
                })
                .appendTo('body')
                .delay(3000)
                .fadeOut(400, function() { $(this).remove(); });
        }
    });
    $('#select-max-coords').on('click', function(e) {
        e.preventDefault();
        selectingMaxCoords = true;
        $(map.getContainer()).css('cursor', 'help');
        
    // Скроллим страницу к верху карты
        $('html, body').animate({
            scrollTop: $(map.getContainer()).offset().top
        }, 500);

        // Показываем мягкое уведомление на экране
        if ($('#coord-hint').length === 0) {
            $('<div id="coord-hint">{{ __('toponym.click_to_select_max_coords') }}</div>')
                .css({
                    position: 'fixed',
                    bottom: '20px',
                    left: '50%',
                    transform: 'translateX(-50%)',
                    background: '#333',
                    color: '#fff',
                    padding: '10px 20px',
                    borderRadius: '8px',
                    zIndex: 10000,
                    fontSize: '14px'
                })
                .appendTo('body')
                .delay(3000)
                .fadeOut(400, function() { $(this).remove(); });
        }
    });

    // Обработка клика по карте
    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(6);
        var lon = e.latlng.lng.toFixed(6);

        if (selectingMinCoords) {
            $minLat.val(lat);
            $minLon.val(lon);
            $minLat.focus();
            selectingMinCoords = false;
        }

        if (selectingMaxCoords) {
            $maxLat.val(lat);
            $maxLon.val(lon);
            $maxLat.focus();
            selectingMaxCoords = false;
        }

        // Возвращаем обычный курсор и сбрасываем режим
        $(map.getContainer()).css('cursor', '');

//        alert(`Установлены координаты: широта ${lat}, долгота ${lon}`);
    });      
    </script>
@endif    
