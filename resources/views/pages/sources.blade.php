@extends('layouts.master')

@section('headTitle', trans('navigation.sources'))
@section('header', trans('navigation.sources'))
    
@section('main')   
    {!!trans('page.sources', ['link2sources' => route('sources.index')])!!}
    
@stop