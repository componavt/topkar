@extends('layouts.page')

@section('headTitle', $selsovet->name. ' — '. trans('messages.editing'))
@section('header', trans('navigation.selsovets1926'))

@section('page_top')   
    <h2>{{ trans('messages.editing'). ' / '. $selsovet->name }}</h2>
@endsection

@section('top_links')   
    {!! to_list('selsovets1926', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('selsovets1926', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection    
    
@section('content')       
    {!! Form::model($selsovet, array('method'=>'PUT', 'route' => ['selsovets1926.update', $selsovet->id], 'id'=>'selsovet1926Form')) !!}
    @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
    @include('dict.selsovets1926._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
@stop
