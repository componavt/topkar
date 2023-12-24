@extends('layouts.page')

@section('headTitle', $geotype->name)
@section('header', trans('navigation.geotypes'))

@section('page_top')   
    <h2>{{ $geotype->name }}</h2>
@endsection            

@section('top_links')   
    {!! to_list('geotypes', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_edit('geotypes', $geotype, $args_by_get) !!}
        {!! to_delete('geotypes', $geotype, $args_by_get) !!}
        {!! to_create('geotypes', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection            

@section('content')   
    @if (user_can_edit() || $geotype->name_ru || $geotype->short_ru || $geotype->desc_ru)
        <h3>{{mb_ucfirst(trans('messages.in_russian'))}}</h3>
        
        @if (user_can_edit() || $geotype->name_ru)
        <p><span class='field-name'>{{trans('toponym.name')}}</span>: 
        <span class='field-value'>{{ $geotype->name_ru }}</span></p>
        @endif             

        @if (user_can_edit() || $geotype->short_ru)
        <p><span class='field-name'>{{trans('misc.short_name')}}</span>: 
        <span class='field-value'>{{ $geotype->short_ru }}</span></p>
        @endif             

        @if (user_can_edit() || $geotype->desc_ru)
        <p><span class='field-name'>{{trans('misc.desc')}}</span>: 
        <span class='field-value'>{{ $geotype->desc_ru }}</span></p>
        @endif             
    @endif             

    @if (user_can_edit() || $geotype->name_en || $geotype->short_en || $geotype->desc_en)
        <h3>{{mb_ucfirst(trans('messages.in_english'))}}</h3>
        
        @if (user_can_edit() || $geotype->name_en)
        <p><span class='field-name'>{{trans('toponym.name')}}</span>: 
        <span class='field-value'>{{ $geotype->name_en }}</span></p>
        @endif             

        @if (user_can_edit() || $geotype->short_en)
        <p><span class='field-name'>{{trans('misc.short_name')}}</span>: 
        <span class='field-value'>{{ $geotype->short_en }}</span></p>
        @endif             

        @if (user_can_edit() || $geotype->desc_en)
        <p><span class='field-name'>{{trans('misc.desc')}}</span>: 
        <span class='field-value'>{{ $geotype->desc_en }}</span></p>
        @endif             
    @endif             
@endsection
    
@section('footScriptExtra')
        {!!Html::script('js/rec-delete-link.js')!!}
@endsection
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
@stop
