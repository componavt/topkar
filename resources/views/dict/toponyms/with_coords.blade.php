@extends('layouts.master')

@section('headTitle', trans('navigation.on_map'))

@section('headExtra')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
        {!!Html::style('css/leaflet.css')!!}  
@stop

@section('header', trans('navigation.toponyms_with_coords'))

@section('main')   
    @include('widgets.found_records', ['n_records'=>$n_records])
    <div id="mapid" style="width: 100%; height: 1400px;"></div>
@stop

@section('footScriptExtra')
        @include('dict.toponyms.toponyms_on_map')
@stop

