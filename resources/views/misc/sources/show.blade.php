@extends('layouts.master')

@section('header', trans('navigation.sources'))

@section('main')   
    <h3>{{ $source->name }}</h3>
    
    <div class='top-links'>        
        <a href="{{ route('sources.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | @include('widgets.form.button._edit', ['route' => route('sources.edit', $source)])
            | @include('widgets.form.button._delete', ['route' => 'sources.destroy', 'args'=>['source' => $source->id]])             
            | <a href="{{ route('sources.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.edit') }} | {{ trans('messages.delete') }} | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
    
    <h3>{{mb_ucfirst(trans('messages.in_russian'))}}</h3>
    <p><span class='field-name'>{{trans('toponym.name')}}</span>: 
    <span class='field-value'>{{ $source->name_ru }}</span></p>

    <p><span class='field-name'>{{trans('misc.short_name')}}</span>: 
    <span class='field-value'>{{ $source->short_ru }}</span></p>

    <h3>{{mb_ucfirst(trans('messages.in_english'))}}</h3>
    <p><span class='field-name'>{{trans('toponym.name')}}</span>: 
    <span class='field-value'>{{ $source->name_en }}</span></p>

    <p><span class='field-name'>{{trans('misc.short_name')}}</span>: 
    <span class='field-value'>{{ $source->short_en }}</span></p>
@stop

@section('footScriptExtra')
        {!!Html::script('js/rec-delete-link.js')!!}
@stop
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
@stop
