    var marker;
    var settl_coords = $("#settlement-coords");
    var zoom = 12;
    var long, longitude = $('#longitude').val();
    if (longitude) {
        long = longitude;
    } else {
        long = 33.4;
        zoom = 6;
    }
    var lat, latitude = $('#latitude').val();
    if (latitude) {
        lat = latitude;
    } else {
        lat = 63.8;
        zoom = 6;
    }
      
    var map = L.map('mapid').setView({lon:long , lat: lat}, zoom);
    
    setInterval(function () {
       map.invalidateSize();
    }, 100);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
    }).addTo(map);

    L.control.scale().addTo(map);

    if (longitude && latitude) {
        marker=L.marker({lon:longitude , lat: latitude}).bindPopup($('#name').val()).addTo(map);
    }
    
    map.on('click', 
        function(e){
            var coord = e.latlng.toString().split(',');
            var lat = coord[0].split('(');
            lat = lat[1].trim();
            var lng = coord[1].split(')');
            lng = lng[0].trim();
console.log("You clicked the map at latitude: " + lat + " and longitude:" + lng);
            $('#longitude').val(lng);
            $('#latitude').val(lat);
            moveMarker(map, marker, lng, lat);
            setTimeout(function() {
                $("#modalMap").modal('hide');
            }, 1000);
            $('#latitude').focus();
        });

    settl_coords.on('click', 
        function(e){
            var lng = $(this).data('lon')
            var lat = $(this).data('lat')
            $('#longitude').val(lng);
            $('#latitude').val(lat);
            $('#latitude').focus();
//console.log(marker);            
            moveMarker(map, marker, lng, lat);
        });
        
    $("#longitude").on('click', function (e) {
        if ($(this).val()==='') {
            var coord =$("#latitude").val().split(',');
            var lat = coord[0].trim();
            var lng = coord[1].trim();
            if (lng !== undefined && lng !== null) {
                $("#latitude").val(lat);
                $("#longitude").val(lng);
                moveMarker(map, marker, lng, lat);
            }
        }
    });
        