@extends('layouts.page')

@section('headTitle', $informant->name)
@section('header', trans('navigation.informants'))

@section('page_top')   
    <h2>{{ $informant->name }}</h2>
@endsection            

@section('top_links')   
    {!! to_list('informants', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_edit('informants', $informant, $args_by_get) !!}
        {!! to_delete('informants', $informant, $args_by_get) !!}
        {!! to_create('informants', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection            

@section('content')   
    <p><span class='field-name'>{{trans('messages.name')}} {{trans('messages.in_russian')}}</span>: 
    <span class='field-value'>{{ $informant->name_ru }}</span></p>

    @if (user_can_edit() || $informant->name_en)
    <p><span class='field-name'>{{trans('messages.name')}} {{trans('messages.in_english')}}</span>: 
    <span class='field-value'>{{ $informant->name_en }}</span></p>
    @endif
    
    @if (user_can_edit() || $informant->birth_date)
    <p><span class='field-name'>{{trans('misc.birth_date')}}</span>: 
    <span class='field-value'>{{ $informant->birth_date }}</span></p>
    @endif
@endsection

@section('footScriptExtra')
        {!!Html::script('js/rec-delete-link.js')!!}
@endsection
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
@stop
