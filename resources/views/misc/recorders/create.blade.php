@extends('layouts.page')

@section('headTitle', trans('messages.new_m'). ' '. mb_strtolower(__('misc.recorder')))
@section('header', trans('navigation.recorders'))

@section('page_top')   
    <h2>{{ trans('messages.new_m'). ' '. mb_strtolower(trans('misc.recorder')) }}</h2>
@endsection

@section('top_links')   
    {!! to_list('recorders', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('recorders', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection    
    
@section('content')   
    {!! Form::open(['method'=>'POST', 'route' => ['recorders.store'], 'id'=>'recorderForm']) !!}
    @include('misc.recorders._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.create')])
    {!! Form::close() !!}
@stop
