@extends('layouts.page')

@section('headTitle', $district->name. ' — '. trans('messages.editing'))
@section('header', trans('navigation.districts'))

@section('page_top')   
    <h2>{{ trans('messages.editing'). ' / '. $district->name }}</h2>
@endsection

@section('top_links')   
    {!! to_list('district', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('district', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection    
    
@section('content')       
    {!! Form::model($district, array('method'=>'PUT', 'route' => ['districts.update', $district->id], 'id'=>'districtForm')) !!}
    @include('dict.districts._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
@stop
