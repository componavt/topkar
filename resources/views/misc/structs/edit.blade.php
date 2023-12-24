@extends('layouts.page')

@section('headTitle', $struct->name. ' — '. trans('messages.editing'))
@section('header', trans('navigation.structs'). ' / '. trans('messages.editing'). ' / '. $struct->name)

@section('page_top')   
    <h2>{{ trans('messages.editing'). ' / '. $struct->name }}</h2>
@endsection

@section('top_links')   
    {!! to_list('structs', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('structs', $args_by_get, trans('messages.create_new_f')) !!}
    @endif             
@endsection    
    
@section('content')       
    {!! Form::model($struct, array('method'=>'PUT', 'route' => ['structs.update', $struct->id], 'id'=>'structForm')) !!}
    @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
    @include('misc.structs._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
@stop
