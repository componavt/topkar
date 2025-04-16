@include('widgets.leaflet.coords_from_click')

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
        selectEventSettlement('region_id', '{{app()->getLocale()}}', '', true, '.select-event-place{{$i}}', '#toponymForm');
        selectEventSettlement1926('region_id', '{{app()->getLocale()}}', '', true, '.select-event-place1926_{{$i}}', '#toponymForm');
        selectInformant('{{app()->getLocale()}}', '', true, '.select-informant{{$i}}', '#toponymForm');
        selectRecorder('{{app()->getLocale()}}', '', true, '.select-recorder{{$i++}}', '#toponymForm');
    @endforeach
@endif
selectEventSettlement('region_id', '{{app()->getLocale()}}', '', true, '.select-event-place{{$i}}', '#toponymForm');
selectEventSettlement1926('region_id', '{{app()->getLocale()}}', '', true, '.select-event-place1926_{{$i}}', '#toponymForm');
selectInformant('{{app()->getLocale()}}', '', false, '.select-informant{{$i}}', '#toponymForm');
selectRecorder('{{app()->getLocale()}}', '', false, '.select-recorder{{$i}}', '#toponymForm');

