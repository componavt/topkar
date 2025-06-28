@extends('layouts.page')

@section('headTitle', trans('navigation.toponyms'))
@section('header', trans('navigation.toponyms'))

@section('headExtra')
        {!! css('history') !!}  
@endsection

@section('page_top')   
    <h2>{{ $toponym->name }}</h2>
    <h3>{{ trans('messages.history') }}</h3>
@endsection            

@section('top_links')   
    {!! back_to_show('toponym', $toponym, $args_by_get) !!}
    {!! to_list('toponym', $args_by_get) !!}
@endsection            

@section('content')   
        @include('widgets._history', ['all_history' => $toponym->allHistory()])
@stop        