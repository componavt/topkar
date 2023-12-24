@extends('layouts.page')

@section('headTitle', trans('messages.new_m'). ' '. mb_strtolower(trans('toponym.district1926')))
@section('header', trans('navigation.districts1926'))

@section('page_top')   
    <h2>{{ trans('messages.new_m'). ' '. mb_strtolower(trans('toponym.district1926')) }}</h2>
@endsection

@section('top_links')   
    {!! to_list('district1926', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('district1926', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection    
    
@section('content')   
    {!! Form::open(['method'=>'POST', 'route' => ['districts1926.store'], 'id'=>'districtForm']) !!}
    @include('dict.districts1926._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.create')])
    {!! Form::close() !!}
@stop
