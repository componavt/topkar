    var long, longitude = $('#longitude').val();
    if (longitude) {
        long = longitude;
    } else {
        long = 33.4;
    }
    var lat, latitude = $('#latitude').val();
    if (latitude) {
        lat = latitude;
    } else {
        lat = 63.8;
    }
      
    var map = L.map('mapid').setView({lon:long , lat: lat}, 6);
    
    setInterval(function () {
       map.invalidateSize();
    }, 100);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
    }).addTo(map);

    L.control.scale().addTo(map);

    if (longitude && latitude) {
        L.marker({lon:longitude , lat: latitude}).bindPopup($('#name').val()).addTo(map);
    }
    
    map.on('click', 
        function(e){
            var coord = e.latlng.toString().split(',');
            var lat = coord[0].split('(');
            var lng = coord[1].split(')');
console.log("You clicked the map at latitude: " + lat[1].trim() + " and longitude:" + lng[0].trim());
            $('#longitude').val(lng[0].trim());
            $('#latitude').val(lat[1].trim());
console.log($('#longitude').val());            
            $("#modalMap").modal('hide');
            $('#latitude').focus();
        });
