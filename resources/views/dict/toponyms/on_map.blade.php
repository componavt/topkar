@extends('layouts.master')

@section('headTitle', trans('navigation.on_map'))

@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}  
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
<link rel="stylesheet" href="/css/map.css"/>
@stop

@section('header', trans('navigation.toponyms'))

@section('search_form')   
    <h2>{{ trans('navigation.search_by_toponyms'). ' '. mb_strtolower(trans('navigation.on_map')) }}</h2>
    @include("dict.toponyms.form._search", ['route' => route('toponyms.on_map')])
     <div class="row" style='line-height: 26px;'>  
         <div class="col-sm-6">
    @if ($show_count == $total_rec)
        @include('widgets.found_records', ['n_records'=>$show_count])
    @else
        <p>{!! __('toponym.found_from', 
            ['show_count'=>number_format($show_count, 0, ',', ' '), 
             'total'=>number_format($total_rec, 0, ',', ' ')]) !!}
        </p>
    @endif
         </div>
         <div class="col-sm-6 output_in">
            <a class="big" href="{{ route('toponyms.index').$args_by_get }}">{!! trans('toponym.back_to_index') !!}</a>
         </div>
    </div>
@endsection

@section('main')   
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-sm-4"><img src="/images/markers/marker-icon-blue.png" class="legend-icon"> 
            топонимы с точными координатами</div>
        <div class="col-sm-4"><img src="/images/markers/marker-icon-grey.png" class="legend-icon"> 
            топонимы, привязанные к координатам поселения</div>
        <div class="col-sm-4"><img src="/images/markers/marker-icon-violet.png" class="legend-icon"> 
            топонимы и поселения с одинаковыми координатами</div>
    </div>
    <div id="mapid" style="width: 100%; height: 1700px;"></div>
@stop

@section('footScriptExtra')
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/lists.js')!!}
        {!!Html::script('js/special_symbols.js')!!}
        @include('widgets.leaflet.objs_on_map', ['lon'=>0, 'lat'=>0, 'zoom'=>2])
@endsection

@section('jqueryFunc')
        $('.select-geotype').select2({allowClear: false, placeholder: '{{trans('misc.geotype')}}'});
        $('.select-informant').select2({allowClear: false, placeholder: '{{trans('navigation.informants')}}'});
        $('.select-recorder').select2({allowClear: false, placeholder: '{{trans('navigation.recorders')}}'});
        $('.select-region').select2({allowClear: false, placeholder: '{{trans('toponym.region')}}'});
        $('.select-region1926').select2({allowClear: false, placeholder: '{{trans('toponym.region1926')}}'});
        $('.select-source').select2({allowClear: false, placeholder: '{{trans('toponym.source')}}'});
        $('.select-structhier').select2({allowClear: false, placeholder: '{{trans('misc.structhier')}}'});
        $('.select-ethnos_territory').select2({allowClear: false, placeholder: '{{trans('misc.ethnos_territory')}}'});
        $('.select-etymology_nation').select2({allowClear: false, placeholder: '{{trans('misc.etymology_nation')}}'});
        
        selectDistrict('search_regions', '{{app()->getLocale()}}', '{{trans('toponym.district')}}', false);
        selectSettlement('search_regions', 'search_districts', '{{app()->getLocale()}}', '{{trans('toponym.settlement')}}', false);
        selectSettlement('search_regions', 'search_districts', '{{app()->getLocale()}}', '{{trans('misc.record_place')}}', false, '.select-record-place');
        selectDistrict1926('search_regions1926', '{{app()->getLocale()}}', '{{trans('toponym.district1926')}}', false);
        selectSelsovet1926('search_regions1926', 'search_districts1926', '{{app()->getLocale()}}', '{{trans('toponym.selsovet1926')}}', false);
        selectSettlement1926('search_regions1926', 'search_districts1926', 'search_selsovets1926', '{{app()->getLocale()}}', '{{trans('toponym.settlement1926')}}', false);
        selectStruct('search_structhiers', '{{app()->getLocale()}}', '{{trans('misc.struct')}}', false);
        
        $('input[type=reset]').on('click', function (e) {
        @foreach (['geotypes', 'ethnos_territories', 'etymology_nations', 'regions',
                   'districts', 'settlements', 'record_places', 'regions1926', 
                   'districts1926', 'selsovets1926', 'settlements1926', 'sources', 
                   'structhiers', 'structs', 'informants', 'recorders'] as $f)
            $('#search_{{ $f }}').val(null).trigger('change');
        @endforeach
            $('#search_toponym').attr('value','');
        });        
@stop

