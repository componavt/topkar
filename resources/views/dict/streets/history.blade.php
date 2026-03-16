@extends('layouts.page')

@section('headTitle', trans('navigation.streets'))
@section('header', trans('navigation.streets'))

@section('headExtra')
        {!! css('history') !!}  
@endsection

@section('page_top')   
    <h2>{{ $street->name }}</h2>
    <h3>{{ trans('messages.history') }}</h3>
@endsection            

@section('top_links')   
    {!! to_edit('street', $street, $args_by_get) !!}
    {!! back_to_show('street', $street, $args_by_get) !!}
    {!! to_list('street', $args_by_get) !!}
@endsection            

@section('content')   
        @include('widgets._history', ['all_history' => $street->allHistory()])
@stop        