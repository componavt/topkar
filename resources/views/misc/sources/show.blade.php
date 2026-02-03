@extends('layouts.page')

@section('headTitle', $source->name)
@section('header', trans('navigation.sources'))

@section('page_top')   
    <h2>{{ $source->name }}</h2>
@endsection            

@section('top_links')   
    {!! to_list('sources', $args_by_get) !!}
    @if (user_can_edit())
        {!! to_edit('sources', $source, $args_by_get) !!}
        {!! to_delete('sources', $source, $args_by_get) !!}
        {!! to_create('sources', $args_by_get, trans('messages.create_new_m')) !!}
    @endif             
@endsection            

@section('content')

    @if (user_can_edit() || $source->year)
    <p style="margin-top: 20px"><span class='h3'>{{ mb_ucfirst(trans('messages.year')) }}</span>: 
        <span class='field-value'>{{ $source->year }}</span></p>
    @endif 
    
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
    
    <h3>
        {{ trans('navigation.toponyms') }}: 
        @if ($source->toponyms->count() > 0)
        <a href="{{route('toponyms.index')}}?search_sources[]={{$source->id}}">{{ $source->toponyms->count() }}</a>
        @else
        0
        @endif
    </h3>
    
@stop

@section('footScriptExtra')
        {!!Html::script('js/rec-delete-link.js')!!}
@stop
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
@stop
