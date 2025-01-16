@extends('layouts.master')

@section('headTitle', 'Шайдомозеро')

@section('headExtra')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
        {!!Html::style('css/leaflet.css')!!}  
@stop

@section('header', 'Шайдомозеро')

@section('main')   
    @include('widgets.found_records', ['n_records'=>$n_records])
    <div id="mapid" style="width: 1350px; height: 1500px;"></div>
@stop

@section('footScriptExtra')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
       integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
       crossorigin=""></script>

    <script>
      // initialize Leaflet
      var map = L.map('mapid').setView({lon:34.18 , lat: 62.715}, 14);

      // add the OpenStreetMap tiles
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
      }).addTo(map);

      // show the scale bar on the lower left corner
      L.control.scale().addTo(map);

      // show markers on the map
      @foreach ($objs as $obj)
      L.marker({lon:{{ $obj['lon'] }} , lat: {{ $obj['lat'] }} }).bindPopup('{!! $obj["popup"] !!}', {autoClose:false}).addTo(map).openPopup();
      @endforeach
    </script>
@stop

