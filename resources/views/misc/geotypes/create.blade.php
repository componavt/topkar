@extends('layouts.page')

@section('headTitle', trans('messages.new_g'). ' '. mb_strtolower(__('toponym.geotypes')))
@section('header', trans('navigation.geotypes'))

@section('page_top')   
    <h2>{{ trans('messages.new_g'). ' '. mb_strtolower(trans('toponym.geotypes')) }}</h2>
@endsection

@section('top_links')   
    {!! to_list('geotypes', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('geotypes', $args_by_get, trans('messages.create_new_f')) !!}
    @endif             
@endsection    
    
@section('content')   
    {!! Form::open(['method'=>'POST', 'route' => ['geotypes.store'], 'id'=>'geotypeForm']) !!}
    @include('misc.geotypes._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.create')])
    {!! Form::close() !!}
@stop
