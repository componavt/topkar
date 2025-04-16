@extends('layouts.master')

@section('head_master')
<link rel="stylesheet" href="/css/page.css"/>
@stop

@section('wide-block')   
<div class="blank">
    <div class="page-top-links">
        <div class="page-top">
            @yield('page_top')
        </div>
        <div class="top-links">
            @yield('top_links')
        </div>
    </div>

    @yield('content')
</div>
@endsection