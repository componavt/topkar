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
//                    console.log("You clicked the map at latitude: " + lat[1] + " and longitude:" + lng[0]);
                    $('#longitude').val(lng[0].trim());
                    $('#latitude').val(lat[1].trim());
                    $("#modalMap").modal('hide');
                    $('#latitude').focus();
            });

$('.select-geotype').select2({allowClear: true, placeholder: ''});
        $('.select-etymology-nation').select2({allowClear: true, placeholder: ''});
        $('.select-ethnos-territory').select2({allowClear: true, placeholder: ''});
        $('.select-region').select2({allowClear: true, placeholder: ''});
        $('.select-region1926').select2({allowClear: true, placeholder: ''});
        
selectSettlement('region_id', 'district_id', '{{app()->getLocale()}}', '', false, '.select-settlement', '#toponymForm');
        selectDistrict('region_id', '{{app()->getLocale()}}', '', true, '.select-district', '#toponymForm');
        selectDistrict1926('region1926_id', '{{app()->getLocale()}}', '', true, '.select-district1926', '#toponymForm');
        selectSettlement('region_id', 'district_id', '{{app()->getLocale()}}', '', true, '.select-settlement', '#toponymForm');
        selectSelsovet1926('region1926_id', 'district1926_id', '{{app()->getLocale()}}', '', true, '.select-selsovet1926', '#toponymForm');
        selectSettlement1926('region1926_id', 'district1926_id', 'selsovet1926_id', '{{app()->getLocale()}}', '', true, '.select-settlement1926', '#toponymForm');

@for ($i=0; $i<4; $i++)
        selectStruct('structhiers_{{$i}}_', '{{app()->getLocale()}}', '', true, '.select-struct{{$i}}', '#toponymForm');
@endfor

<?php $i=1; ?>
@if ($action == 'edit') 
    @foreach ($toponym->events as $event)
        selectSettlement('region_id', 'district_id', '{{app()->getLocale()}}', '', true, '.select-event-place{{$i}}', '#toponymForm');
        selectInformant('{{app()->getLocale()}}', '', true, '.select-informant{{$i}}', '#toponymForm');
        selectRecorder('{{app()->getLocale()}}', '', true, '.select-recorder{{$i++}}', '#toponymForm');
    @endforeach
@endif
selectSettlement('region_id', 'district_id', '{{app()->getLocale()}}', '', false, '.select-event-place{{$i}}', '#toponymForm');
selectInformant('{{app()->getLocale()}}', '', false, '.select-informant{{$i}}', '#toponymForm');
selectRecorder('{{app()->getLocale()}}', '', false, '.select-recorder{{$i}}', '#toponymForm');
