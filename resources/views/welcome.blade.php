@extends('layouts.start')

@section('headExtra')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
@stop

@section('header', trans('page.site_title'))

@section('main')   
    <div id='welcome-text'>
        {!!trans('page.welcome_text_intro')!!}
    </div>
    <div class='bar'></div>
    
    <div class="row">
        <div class="col-sm-6" style="margin-bottom: 30px">
            <a class="toponym-title" href="{{ route('toponyms.show',$toponym) }}">{{ $toponym->name }}</a>
            <div id="mapid" style="width: 100%; height: 400px;"></div>            
        </div>
        <div class="col-sm-6" style="margin-bottom: 30px">
            {!!trans('page.welcome_text_content')!!}<br><br>
            <span class="imp">{!!trans('page.welcome_reference_tables')!!}</span>
        </div>
    </div>
    <div id="start-text">
    {!!trans('page.welcome_text_sources')!!}<br><br>
    {!!trans('page.welcome_text_software')!!}
    {!!trans('page.welcome_who_can_use')!!}
    </div>
@stop

@section('footScriptExtra')
        @include('dict.toponyms.toponym_on_map')
@stop
