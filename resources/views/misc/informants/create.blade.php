@extends('layouts.page')

@section('headTitle', trans('messages.new_m'). ' '. mb_strtolower(__('misc.informants')))
@section('header', trans('navigation.informants'))

@section('page_top')   
    <h2>{{ trans('messages.new_m'). ' '. mb_strtolower(trans('misc.informant')) }}</h2>
@endsection

@section('top_links')   
    {!! to_list('informants', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('informants', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection    
    
@section('content')   
    {!! Form::open(['method'=>'POST', 'route' => ['informants.store'], 'id'=>'informantForm']) !!}
    @include('misc.informants._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.create')])
    {!! Form::close() !!}
@stop
