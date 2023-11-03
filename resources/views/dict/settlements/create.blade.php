@extends('layouts.master')

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}  
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
         integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
         crossorigin=""/>
@endsection

@section('header', trans('navigation.settlements'). ' / '. trans('messages.new_g'). ' '. mb_strtolower(trans('toponym.settlement')))

@section('main')   
    @include('widgets.modal',['name'=>'modalMap',
                          'title'=>trans('toponym.coords_from_map'),
                          'modal_view'=>'widgets.leaflet.karelia_on_map'])
                          
    <div class='top-links'>        
        <a href="{{ route('settlements.index') }}{{$args_by_get}}">{{ __('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('settlements.create') }}{{$args_by_get}}">{{ mb_strtolower(__('messages.create_new_m')) }}</a>
        @else
            | {{ __('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::open(['method'=>'POST', 'route' => ['settlements.store'], 'id'=>'settlementForm']) !!}
    @include('dict.settlements._form_create_edit', [
        'settlement'=>null, 
        'with_coords' => true,
        'district_value'=>[], 
        'action'=>'creation'])
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
    selectDistrict('region_id', '{{app()->getLocale()}}', '{{trans('toponym.select_district')}}', true, '.select-district-0');
    
    $('.select-district-0').select2({
        allowClear: true,
        placeholder: '{{trans('toponym.select_district')}}',
        width: '100%'
    });      
    
    @include('widgets.leaflet.coords_from_click')
@stop
