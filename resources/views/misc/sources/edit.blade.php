@extends('layouts.master')

@section('header', trans('navigation.sources'). ' / '. trans('messages.editing'). ' / '. $source->name)

@section('main')   
    <div class='top-links'>        
        <a href="{{ route('sources.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('sources.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::model($source, array('method'=>'PUT', 'route' => ['sources.update', $source->id], 'id'=>'sourceForm')) !!}
    @include('misc.sources._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
@stop
