@extends('layouts.master')

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}  
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
         integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
         crossorigin=""/>
@endsection
    
@section('headTitle', $toponym->name. ' — '. trans('messages.editing'))
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
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
       integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
       crossorigin=""></script>
<script>
    
</script>
@endsection
@section('jqueryFunc')
        @include('dict.toponyms._jquery_func_for_create_edit', ['action' => 'edit'])
@stop
