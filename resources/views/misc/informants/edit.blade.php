@extends('layouts.page')

@section('headTitle', $informant->name. ' — '. trans('messages.editing'))
@section('header', trans('navigation.informants'))

@section('page_top')   
    <h2>{{ $informant->name. ': '. trans('messages.editing') }}</h2>
@endsection

@section('top_links')   
    {!! back_to_show('informants', $informant, $args_by_get) !!}
    {!! to_list('informants', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_create('informants', $args_by_get, trans('messages.create_new_g')) !!}
    @endif             
@endsection    
    
@section('content')       
    {!! Form::model($informant, array('method'=>'PUT', 'route' => ['informants.update', $informant->id], 'id'=>'informantForm')) !!}
    @include('misc.informants._form_create_edit')
    @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
    {!! Form::close() !!}
@stop
