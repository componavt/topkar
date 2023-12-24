@extends('layouts.page')

@section('headTitle', trans('messages.new_m'). ' '. mb_strtolower(trans('toponym.selsovet')))
@section('header', trans('navigation.selsovets1926'))

@section('page_top')   
    <h2>{{ trans('messages.new_m'). ' '. mb_strtolower(trans('toponym.selsovet')) }}</h2>
@endsection

@section('top_links')   
    {!! to_list('selsovets1926', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('selsovets1926', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection    
    
@section('content')   
    {!! Form::open(['method'=>'POST', 'route' => ['selsovets1926.store'], 'id'=>'selsovet1926Form']) !!}
    @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
    @include('dict.selsovets1926._form_create_edit', ['selsovet'=>null])
    @include('widgets.form.formitem._submit', ['title' => trans('messages.create')])
    {!! Form::close() !!}
@stop
