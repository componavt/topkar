<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
@include('header._google_analytics')    
@include('header._head')
</head>
<body>
    @include('header._google_tagmanager')
    <header id="header">
        <div class="container">
            <a href="/"><img id="start-logo" src="/images/logo_ru.png"></a>
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

        @hasSection('search_form')
            <div class="search-form">
            @yield('search_form')
            </div>                
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
