@if (!empty($objs))
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
       integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
       crossorigin=""></script>

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

      // show markers on the map
      @foreach ($objs as $obj)
      L.marker({ lon:{{ $obj['lon'] }} , lat: {{ $obj['lat'] }} }, 
               { icon: {{ $obj['color'] }}Icon })
              .bindPopup('{!! $obj["popup"] !!}'
            @if ($obj['color'] != 'blue' && mb_strlen($obj["popup"]) > 300) 
                ,{maxWidth : {{ mb_strlen($obj["popup"]) < 2400 ? 300+round((mb_strlen($obj["popup"])-300)/3) : 1000 }}}
            @endif).addTo(map);
      @endforeach
    </script>
@endif    
