@extends('layouts.page')

@section('headTitle', $source->name. ' — '. trans('messages.editing'))
@section('header', trans('navigation.sources'))

@section('page_top')   
    <h2>{{ trans('messages.editing'). ' / '. $source->name  }}</h2>
@endsection

@section('top_links')   
    {!! back_to_show('sources', $source, $args_by_get) !!}
    {!! to_list('sources', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('sources', $args_by_get, trans('messages.create_new_g')) !!}
    @endif             
@endsection    
    
@section('content')       
    {!! Form::model($source, array('method'=>'PUT', 'route' => ['sources.update', $source->id], 'id'=>'sourceForm')) !!}
    @include('misc.sources._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
@stop
