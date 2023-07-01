@if ($toponym->latitude && $toponym->longitude)
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
       integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
       crossorigin=""></script>

    <script>
      // initialize Leaflet
      var map = L.map('mapid').setView({lon:{{$toponym->longitude}} , lat: {{$toponym->latitude}}}, 14);

      // add the OpenStreetMap tiles
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
      }).addTo(map);

      // show the scale bar on the lower left corner
      L.control.scale().addTo(map);

      // show a marker on the map
      L.marker({lon:{{$toponym->longitude}} , lat: {{$toponym->latitude}}}).bindPopup('Царевичи').addTo(map);
      
    map.on('click', 
            function(e){
                    var coord = e.latlng.toString().split(',');
                    var lat = coord[0].split('(');
                    var lng = coord[1].split(')');
                    console.log("You clicked the map at latitude: " + lat[1] + " and longitude:" + lng[0]);
            });

    </script>
@endif    
