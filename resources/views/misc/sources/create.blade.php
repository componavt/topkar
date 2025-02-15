@extends('layouts.page')

@section('headTitle', trans('messages.new_m'). ' '. mb_strtolower(__('misc.source')))
@section('header', trans('navigation.sources'))

@section('page_top')   
    <h2>{{ trans('messages.new_m'). ' '. mb_strtolower(trans('misc.source')) }}</h2>
@endsection

@section('top_links')   
    {!! to_list('sources', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('sources', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection    
    
@section('content')   
    {!! Form::open(['method'=>'POST', 'route' => ['sources.store'], 'id'=>'sourceForm']) !!}
    @include('misc.sources._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.create')])
    {!! Form::close() !!}
@stop
