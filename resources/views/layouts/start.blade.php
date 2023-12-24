<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
@include('header._google_analytics')    
@include('header._head')
        <link rel="stylesheet" href="/css/start.css">
</head>
<body>
    @include('header._google_tagmanager')
    <header id="start-header">
        <div class="container">
            <a href="/"><img id="start-logo" src="/images/logo_start_{{ app()->getLocale() }}.png"></a>
            @include('header._left_menu')
            @include('header._right_menu')
        </div>
    </header>
    @include('errors._errmsg')

    <div class="container">
        <!-- Page Heading -->
    @hasSection('header')
        <h1>@yield('header')</h1> 
    @endif

        <!-- Page Content -->
        <main>
            @include('layouts.errmsg')
            @yield('main')
        </main>           
    </div>

@include('footer._footer')
@include('footer._foot_script')
</body>
</html>
