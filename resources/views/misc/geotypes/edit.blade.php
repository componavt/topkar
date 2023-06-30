@extends('layouts.master')

@section('header', trans('navigation.geotypes'). ' / '. trans('messages.editing'). ' / '. $geotype->name)

@section('main')   
    <div class='top-links'>        
        <a href="{{ route('geotypes.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | <a href="{{ route('geotypes.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
        
    {!! Form::model($geotype, array('method'=>'PUT', 'route' => ['geotypes.update', $geotype->id], 'id'=>'geotypeForm')) !!}
    @include('misc.geotypes._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
@stop
