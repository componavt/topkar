@extends('layouts.master')

@section('headTitle', trans('navigation.on_map'))

@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}  
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
@stop

@section('header', trans('navigation.toponyms_with_coords'))

@section('search_form')   
        @include("dict.toponyms.form._search")
         <div class="row" style='line-height: 26px;'>  
             <div class="col-sm-4">
        @if ($n_records < 1000)
            @include('widgets.found_records', ['n_records'=>$n_records])
        @else
            <p>{!! __('toponym.found_from', ['total'=>number_format($n_records, 0, ',', ' ')]) !!}</p>
        @endif
             </div>
             <div class="col-sm-8">
                <a href="{{ route('toponyms.index').$args_by_get }}">{{ trans('toponym.back_to_index') !!}</a>
             </div>
        </div>
@endsection

@section('main')   
            <div id="mapid" style="width: 100%; height: 1400px;"></div>
@stop

@section('footScriptExtra')
        @include('dict.toponyms.toponyms_on_map')
@stop

