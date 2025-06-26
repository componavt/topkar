@extends('layouts.master')

@section('headExtra')
    {!!Html::style('css/page.css')!!}  
@endsection

@section('headTitle', trans('toponym.new_toponyms'))
@section('header', trans('toponym.new_toponyms'))
    
@section('main')   
        <p><a href="{{ route('toponyms.last_updated') }}">{{trans('toponym.last_updated_toponyms')}}</a></p>
        @include('dict.toponyms._last_toponyms')
@stop


