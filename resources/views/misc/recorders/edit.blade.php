@extends('layouts.page')

@section('headTitle', $recorder->name. ' — '. trans('messages.editing'))
@section('header', trans('navigation.recorders'))

@section('page_top')   
    <h2>{{ $recorder->name. ': '. trans('messages.editing') }}</h2>
@endsection

@section('top_links')   
    {!! back_to_show('recorders', $recorder, $args_by_get) !!}
    {!! to_list('recorders', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('recorders', $args_by_get, trans('messages.create_new_g')) !!}
    @endif             
@endsection    
    
@section('content')       
    {!! Form::model($recorder, array('method'=>'PUT', 'route' => ['recorders.update', $recorder->id], 'id'=>'recorderForm')) !!}
    @include('misc.recorders._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
@stop
