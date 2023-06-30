@extends('layouts.master')

@section('header', trans('navigation.structs'). ' / '. trans('messages.editing'). ' / '. $struct->name)

@section('main')   
    <div class='top-links'>        
        <a href="{{ route('structs.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('structs.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::model($struct, array('method'=>'PUT', 'route' => ['structs.update', $struct->id], 'id'=>'structForm')) !!}
    @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
    @include('misc.structs._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
@stop
