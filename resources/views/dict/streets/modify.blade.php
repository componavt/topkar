@extends('layouts.page')

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
             integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
             crossorigin=""/>
@endsection

@section('header', trans('navigation.streets'))

@include('dict.streets._'.$action)

@section('footScriptExtra')
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/special_symbols.js')!!}
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
       integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
       crossorigin=""></script>
@endsection
@section('jqueryFunc')
    $('.select-geotype').select2({
        allowClear: true,
        placeholder: '{{trans('misc.geotype')}}',
        width: '100%'
    });

    @foreach (array_keys($structhier_values) as $structhier_id)
    $('.select-struct{{$structhier_id}}').select2({
        allowClear: true,
        placeholder: '{{trans('misc.struct')}}',
        width: '100%'
    });
    @endforeach
@stop
