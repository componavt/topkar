@extends('layouts.master')

@section('headExtra')
        {!!Html::style('css/select2.min.css')!!}  
@endsection
    
@section('header', trans('navigation.toponyms'). ' / '. trans('messages.editing'). ' / '. $toponym->name)

@section('main')   
    <div class='top-links'>        
        <a href="{{ route('toponyms.show', $toponym) }}{{$args_by_get}}">{{ trans('messages.back_to_show') }}</a>
        | <a href="{{ route('toponyms.index') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.back_to_list')) }}</a>
        @if (user_can_edit())
            | <a href="{{ route('toponyms.create') }}{{$args_by_get}}">{{ mb_strtolower(trans('messages.create_new_m')) }}</a>
        @else
            | {{ trans('messages.create_new_m') }}
        @endif 
    </div>
    
    @include('dict.toponyms.modals_for_edition', ['selsovet'=>null, 'settlement'=>null])
    
    {!! Form::model($toponym, [
                    'method'=>'PUT', 
                    'route' => ['toponyms.update', $toponym->id], 
                    'id'=>'toponymForm'
    ]) !!}
    @include('dict.toponyms.form._create_edit', 
                ['submit_title' => trans('messages.save'),
                 'action' => 'edit',
                 'topnames' => $toponym->topnames()->count() ? $toponym->topnames : [],
                 'wrongnames' => $toponym->wrongnames()->count() ? $toponym->wrongnames : []])
        {!! Form::close() !!}
@endsection
    
@section('footScriptExtra')
        {!!Html::script('js/select2.min.js')!!}
        {!!Html::script('js/lists.js')!!}
        {!!Html::script('js/toponym.js')!!}
        {!!Html::script('js/special_symbols.js')!!}
@endsection
@section('jqueryFunc')
        @include('dict.toponyms._jquery_func_for_create_edit', ['action' => 'edit'])
@stop
