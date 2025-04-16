@extends('layouts.wide_page')

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}  
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
             integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
             crossorigin=""/>
@endsection
    
@section('modals')   
    @include('dict.toponyms.modals_for_edition', ['selsovet'=>null, 'settlement'=>null])
@endsection

@section('header', trans('navigation.toponyms'))

@include('dict.toponyms._'.$action) 
    
@section('footScriptExtra')
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/lists.js')!!}
    {!!Html::script('js/special_symbols.js')!!}
    {!!Html::script('js/toponym.js')!!}
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
       integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
       crossorigin=""></script>
@endsection
@section('jqueryFunc')
        @include('dict.toponyms._jquery_func_for_create_edit')
@stop
