@extends('layouts.master')

@section('headExtra')
    {!!Html::style('css/page.css')!!}  
@endsection

@section('headTitle', trans('toponym.last_updated_toponyms'))
@section('header', trans('toponym.last_updated_toponyms'))
    
@section('main')   
        <p><a href="{{ route('toponyms.last_created') }}">{{trans('toponym.new_toponyms')}}</a></p>
        @include('dict.toponyms._last_toponyms')
@stop


