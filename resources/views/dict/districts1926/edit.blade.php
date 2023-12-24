@extends('layouts.page')

@section('headTitle', $district1926->name. ' — '. trans('messages.editing'))
@section('header', trans('navigation.districts1926'))

@section('page_top')   
    <h2>{{ trans('messages.editing'). ' / '. $district1926->name }}</h2>
@endsection

@section('top_links')   
    {!! to_list('district1926', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('district1926', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection    
    
@section('content')       
    {!! Form::model($district1926, array('method'=>'PUT', 'route' => ['districts1926.update', ['districts1926'=>$district1926]], 'id'=>'districtForm')) !!}
    @include('dict.districts1926._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
@stop
