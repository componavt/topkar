@extends('layouts.page')

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}  
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
         integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
         crossorigin=""/>
@endsection
    
@section('headTitle', $settlement->name. ' — '. trans('messages.editing'))
@section('header', trans('navigation.settlements'))

@section('modals')   
    @include('widgets.modal',['name'=>'modalMap',
                          'title'=>trans('toponym.coords_from_map'),
                          'modal_view'=>'widgets.leaflet.karelia_on_map'])
@endsection

@section('page_top')   
    <h2>{{ $settlement->name. ': '. trans('messages.editing') }}</h2>
@endsection

@section('top_links')   
    {!! back_to_show('settlement', $settlement, $args_by_get) !!}
    {!! to_list('settlement', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('settlement', $args_by_get, trans('messages.create_new_g')) !!}
    @endif             
@endsection    
    
@section('content')       
    {!! Form::model($settlement, array('method'=>'PUT', 'route' => ['settlements.update', $settlement->id], 'id'=>'settlementForm')) !!}
    @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
    @include('dict.settlements._form_create_edit', 
            ['district_value'=>$settlement->districtValue(), 
             'with_coords' => true,
             'action'=>'edition'])
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
@endsection
    
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
    @for ($i=0; $i<=sizeof($settlement->districtValue()); $i++)
        selectDistrict('region_id', '{{app()->getLocale()}}', '{{trans('toponym.select_district')}}', true, '.select-district-{{$i}}');
    @endfor

    @include('widgets.leaflet.coords_from_click')    
@stop
