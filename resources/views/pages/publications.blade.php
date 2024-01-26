@extends('layouts.master')

@section('headTitle', trans('navigation.publications'))
@section('header', trans('navigation.publications'))
    
@section('main')   
    {!!trans('page.list_of_publications')!!}
    
    @if ( 'ru' == app()->getLocale())
        <div style='margin-left:40px'>    
        @include('widgets.youtube',
                    ['width' => '40%',
                     'height' => '270',
                     'video' => '7khxXmWET5o'
                    ])
        </div>
    @endif
@stop