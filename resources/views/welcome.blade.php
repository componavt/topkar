@extends('layouts.master')

@section('headExtra')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
@stop

@section('header', trans('page.site_title'))

@section('main')   
    <table width='300px' height="300px" style='float:right;'>
        <tr><td>
            <h2 style="margin-top: 0; text-align:center">
                <a href="{{ route('toponyms.show',$toponym) }}">{{ $toponym->name }}</a>
            </h2>
            <div id="mapid" style="width: 100%; height: 300px;"></div>
        </td></tr>
    </table>
    {!!trans('page.welcome_text_intro')!!}<br><br>
    {!!trans('page.welcome_text_content')!!}<br><br>
    {!!trans('page.welcome_reference_tables')!!}<br><br>
    {!!trans('page.welcome_text_sources')!!}<br><br>
    {!!trans('page.welcome_text_software')!!}
    {!!trans('page.welcome_who_can_use')!!}<br><br>
    <div style='text-align: center'>
        <img src="/images/logo_{{app()->getLocale()}}.png">
    </div>
@stop

@section('footScriptExtra')
        @include('dict.toponyms.toponym_on_map')
@stop
