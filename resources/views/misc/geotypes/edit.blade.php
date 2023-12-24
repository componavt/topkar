@extends('layouts.page')

@section('headTitle', $geotype->name. ' — '. trans('messages.editing'))
@section('header', trans('navigation.geotypes'))

@section('page_top')   
    <h2>{{ $geotype->name. ': '. trans('messages.editing') }}</h2>
@endsection

@section('top_links')   
    {!! back_to_show('geotypes', $geotype, $args_by_get) !!}
    {!! to_list('geotypes', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('geotypes', $args_by_get, trans('messages.create_new_g')) !!}
    @endif             
@endsection    
    
@section('content')       
    {!! Form::model($geotype, array('method'=>'PUT', 'route' => ['geotypes.update', $geotype->id], 'id'=>'geotypeForm')) !!}
    @include('misc.geotypes._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
@stop
