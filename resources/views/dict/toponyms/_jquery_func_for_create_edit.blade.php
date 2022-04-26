        $('.select-geotype').select2({allowClear: true, placeholder: ''});
        $('.select-etymology-nation').select2({allowClear: true, placeholder: ''});
        $('.select-ethnos-territory').select2({allowClear: true, placeholder: ''});
        $('.select-region').select2({allowClear: true, placeholder: ''});
        $('.select-region1926').select2({allowClear: true, placeholder: ''});
        
        selectDistrict('region_id', '', true, '.select-district', '#toponymForm');
        selectDistrict1926('region1926_id', '', true, '.select-district1926', '#toponymForm');
{{--        selectDistrict1926('region1926_id', '', true, '.select-selsovet-district1926', '#modalAddSelsovet1926'); --}}
        selectSelsovet1926('region1926_id', 'district1926_id', '', true, '.select-selsovet1926', '#toponymForm');
        selectSettlement1926('region1926_id', 'district1926_id', 'selsovet1926_id', '', true, '.select-settlement1926', '#toponymForm');

@for ($i=0; $i<4; $i++)
        selectStruct('structhiers_{{$i}}_', '', true, '.select-struct{{$i}}', '#toponymForm');
@endfor