@extends('layouts.page')

@section('headTitle', trans('messages.new_f'). ' '. mb_strtolower(__('misc.struct')))
@section('header', trans('navigation.structs'). ' / '. trans('messages.new_f'). ' '. mb_strtolower(__('misc.struct')))

@section('page_top')   
    <h2>{{ trans('messages.new_m'). ' '. mb_strtolower(trans('misc.struct')) }}</h2>
@endsection

@section('top_links')   
    {!! to_list('struct', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('struct', $args_by_get, trans('messages.create_new_f')) !!}
    @endif             
@endsection    
    
@section('content')   
    {!! Form::open(['method'=>'POST', 'route' => ['structs.store'], 'id'=>'structForm']) !!}
    @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
    @include('misc.structs._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.create')])
    {!! Form::close() !!}
@stop
