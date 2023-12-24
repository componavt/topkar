@extends('layouts.page')

@section('headTitle', $recorder->name)
@section('header', trans('navigation.recorders'))

@section('page_top')   
    <h2>{{ $recorder->name }}</h2>
@endsection            

@section('top_links')   
    {!! to_list('recorders', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_edit('recorders', $recorder, $args_by_get) !!}
        {!! to_delete('recorders', $recorder, $args_by_get) !!}
        {!! to_create('recorders', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection            

@section('content')   
    <p><span class='field-name'>{{trans('messages.name')}} {{trans('messages.in_russian')}}</span>: 
    <span class='field-value'>{{ $recorder->name_ru }}</span></p>

    @if (user_can_edit() || $recorder->name_en)
    <p><span class='field-name'>{{trans('messages.name')}} {{trans('messages.in_english')}}</span>: 
    <span class='field-value'>{{ $recorder->name_en }}</span></p>
    @endif             
@endsection

@section('footScriptExtra')
        {!!Html::script('js/rec-delete-link.js')!!}
@endsection
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
@stop
