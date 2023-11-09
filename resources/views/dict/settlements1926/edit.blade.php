@extends('layouts.master')

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}  
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
         integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
         crossorigin=""/>
@endsection
    
@section('header', trans('navigation.settlements_1926'). ' / '. trans('messages.editing'). ' / '. $settlement->name)

@section('main')   
    @include('widgets.modal',['name'=>'modalMap',
                          'title'=>trans('toponym.coords_from_map'),
                          'modal_view'=>'widgets.leaflet.karelia_on_map'])
                          
    <div class='top-links'>        
        <a href="{{ route('settlements1926.show', $settlement) }}{{$args_by_get}}">{{ trans('messages.back_to_show') }}</a>
        | <a href="{{ route('settlements1926.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('settlements1926.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::model($settlement, array('method'=>'PUT', 'route' => ['settlements1926.update', $settlement->id], 'id'=>'settlement1926Form')) !!}
    @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
    @include('dict.settlements1926._form_create_edit', ['with_coords' => true])
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
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
