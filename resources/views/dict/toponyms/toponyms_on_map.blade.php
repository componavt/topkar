    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
       integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
       crossorigin=""></script>

    <script>
      // initialize Leaflet
      var map = L.map('mapid').setView({lon:{{empty($lon) ? '33' : $lon}} , lat: {{empty($lat) ? '63.5' : $lat}}}, {{empty($zoom) ? '7' : $zoom}});

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
      L.marker({lon:{{ $obj['lon'] }} , lat: {{ $obj['lat'] }} }).bindPopup('{!! $obj["popup"] !!}').addTo(map);
      @endforeach
    </script>
 