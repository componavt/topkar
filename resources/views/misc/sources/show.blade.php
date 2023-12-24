@extends('layouts.page')

@section('headTitle', $source->name)
@section('header', trans('navigation.sources'))

@section('page_top')   
    <h2>{{ $source->name }}</h2>
@endsection            

@section('top_links')   
    {!! to_list('recorders', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_edit('recorders', $source, $args_by_get) !!}
        {!! to_delete('recorders', $source, $args_by_get) !!}
        {!! to_create('recorders', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection            

@section('content')   
    @if (user_can_edit() || $source->name_ru || $source->short_ru)
        <h3>{{mb_ucfirst(trans('messages.in_russian'))}}</h3>
        @if (user_can_edit() || $source->name_ru)
        <p><span class='field-name'>{{trans('toponym.name')}}</span>: 
        <span class='field-value'>{{ $source->name_ru }}</span></p>
        @endif 

        @if (user_can_edit() || $source->short_ru)
        <p><span class='field-name'>{{trans('misc.short_name')}}</span>: 
        <span class='field-value'>{{ $source->short_ru }}</span></p>
        @endif 
    @endif 

    @if (user_can_edit() || $source->name_en || $source->short_en)
        <h3>{{mb_ucfirst(trans('messages.in_english'))}}</h3>
        @if (user_can_edit() || $source->name_en)
        <p><span class='field-name'>{{trans('toponym.name')}}</span>: 
        <span class='field-value'>{{ $source->name_en }}</span></p>
        @endif 

        @if (user_can_edit() || $source->short_en)
        <p><span class='field-name'>{{trans('misc.short_name')}}</span>: 
        <span class='field-value'>{{ $source->short_en }}</span></p>
        @endif 
    @endif 
@stop

@section('footScriptExtra')
        {!!Html::script('js/rec-delete-link.js')!!}
@stop
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
@stop
