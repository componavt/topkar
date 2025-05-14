    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
       integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
       crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

    <style>
        .custom-cluster div {
          border-radius: 50%;
          width: 30px;
          height: 30px;
          line-height: 27px;
          text-align: center;
          font-size: 12px;
          font-weight: bold;
          color: white;
          border: 2px solid white;
        }

        .cluster-blue {
          background-color: rgba(0, 100, 161, 0.7);
        }
    </style>

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

      var blueCluster = L.markerClusterGroup({
        iconCreateFunction: function(cluster) {
          return L.divIcon({
            html: '<div class="cluster-blue"><span>' + cluster.getChildCount() + '</span></div>',
            className: 'custom-cluster',
            iconSize: L.point(30, 30)
          });
        }
      });
      
      // show markers on the map
      @foreach ($objs as $obj)
      var marker = L.marker({lon:{{ $obj['lon'] }} , lat: {{ $obj['lat'] }} }).bindPopup('{!! $obj["popup"] !!}');
      blueCluster.addLayer(marker);
      @endforeach
      
      map.addLayer(blueCluster);  
    </script>
 