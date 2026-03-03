@extends('layouts.master')

@section('headTitle', trans('navigation.awards'))
@section('header', trans('navigation.awards'))

@section('headExtra')
    {!! css('fancybox') !!}
@stop

@section('main')
    <div style="overflow: hidden;">
        <div style="float: left; margin-right: 20px; margin-bottom: 10px; max-width: 200px;">
            <a data-fancybox="gallery" href="/images/heritage_big.jpg">
                <img class="img-fluid img-responsive img-rounded" src="/images/heritage.jpg" alt="Heritage Award" style="width: 100%; height: auto;">
            </a>
        </div>
        <div style="overflow: auto;">
            {!!trans('page.award_heritage')!!}
        </div>
    </div>
    <div style="clear: both;"></div>
@stop

@section('footScriptExtra')
    {!! js('fancybox.umd') !!}
@stop
