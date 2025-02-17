@extends('layouts.master')

@section('headTitle', trans('navigation.on_map'))
@section('header', trans('navigation.nladoga'))

@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}  
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
<link rel="stylesheet" href="/css/map.css"/>
@stop


@section('search_form')   
    <h2>{{ trans('navigation.search_by_nladoga'). ' '. mb_strtolower(trans('navigation.on_map')) }}</h2>
    @include("dict.toponyms.form._search_nladoga", ['route' => 'nladoga.on_map'])
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
            <a class="big" href="{{ route('toponyms.nladoga').$args_by_get }}">{!! trans('toponym.back_to_index') !!}</a>
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
    <div id="mapid" style="width: 100%; height: 800px;"></div>
@stop

@section('footScriptExtra')
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/lists.js')!!}
        {!!Html::script('js/special_symbols.js')!!}
        @include('widgets.leaflet.objs_on_map', ['lon'=>30.98, 'lat'=>61.7, 'zoom'=>9])
@endsection

@section('jqueryFunc')
        $('.select-geotype').select2({allowClear: false, placeholder: '{{trans('misc.geotype')}}'});        
        selectDistrict('search_regions', '{{app()->getLocale()}}', '{{trans('toponym.district')}}', false);
        selectSettlement('search_regions', 'search_districts', '{{app()->getLocale()}}', '{{trans('toponym.settlement')}}', false);
        
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

