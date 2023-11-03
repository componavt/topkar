@if ($obj->latitude && $obj->longitude)
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
       integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
       crossorigin=""></script>

    <script>
      // initialize Leaflet
      var map = L.map('mapid').setView({lon:{{$obj->longitude}} , lat: {{$obj->latitude}}}, 9);

      // add the OpenStreetMap tiles
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
      }).addTo(map);

      // show the scale bar on the lower left corner
      L.control.scale().addTo(map);

      // show a marker on the map
      L.marker({lon:{{$obj->longitude}} , lat: {{$obj->latitude}}}).bindPopup('{{$obj->name}}').addTo(map);
    </script>
@endif    
