@extends('layouts.master')

@section('header', trans('navigation.recorders'))

@section('main')   
    <h3>{{ $recorder->name }}</h3>
    
    <div class='top-links'>        
        <a href="{{ route('recorders.index') }}{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a>
        @if (user_can_edit())
            | @include('widgets.form.button._edit', ['route' => route('recorders.edit', $recorder)])
            | @include('widgets.form.button._delete', ['route' => 'recorders.destroy', 'args'=>['recorder' => $recorder->id]])             
            | <a href="{{ route('recorders.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.edit') }} | {{ trans('messages.delete') }} | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
    
    <p><span class='field-name'>{{trans('messages.name')}} {{trans('messages.in_russian')}}</span>: 
    <span class='field-value'>{{ $recorder->name_ru }}</span></p>

    <p><span class='field-name'>{{trans('messages.name')}} {{trans('messages.in_english')}}</span>: 
    <span class='field-value'>{{ $recorder->name_en }}</span></p>
@endsection

@section('footScriptExtra')
        {!!Html::script('js/rec-delete-link.js')!!}
@endsection
@section('jqueryFunc')
        recDelete('{{ trans('messages.confirm_delete') }}');
@stop
