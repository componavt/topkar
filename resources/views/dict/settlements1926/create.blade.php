@extends('layouts.page')

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}  
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
         integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
         crossorigin=""/>
    <link rel="stylesheet" href="/css/map.css"/>
@endsection

@section('headTitle', trans('messages.new_g'). ' '. mb_strtolower(__('toponym.settlement1926')))
@section('header', trans('navigation.settlements1926'))

@section('modals')   
    @include('widgets.modal',['name'=>'modalMap',
                          'title'=>trans('toponym.coords_from_map'),
                          'modal_view'=>'widgets.leaflet.karelia_on_map'])
@endsection

@section('page_top')   
    <h2>{{ trans('messages.new_g'). ' '. mb_strtolower(trans('toponym.settlement1926')) }}</h2>
@endsection

@section('top_links')   
    {!! to_list('settlements1926', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('settlements1926', $args_by_get, trans('messages.create_new_f')) !!}
    @endif             
@endsection    
    
@section('content')   
    {!! Form::open(['method'=>'POST', 'route' => ['settlements1926.store'], 'id'=>'settlement1926Form']) !!}
    @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
    @include('dict.settlements1926._form_create_edit', 
        ['settlement'=>null,
         'with_coords' => true])
    @include('widgets.form.formitem._submit', ['title' => trans('messages.create')])
    {!! Form::close() !!}
@endsection
    
@section('footScriptExtra')
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/lists.js')!!}
    {!!Html::script('js/toponym.js')!!}
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
       integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
       crossorigin=""></script>
@endsection

@section('jqueryFunc')
    selectDistrict1926('region1926_id', '{{app()->getLocale()}}', '', true, '.select-district1926');
    selectSelsovet1926('region1926_id', 'district1926_id', '{{app()->getLocale()}}', '', true, '.select-selsovet1926');

    @include('widgets.leaflet.coords_from_click')
@stop
