<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
@include('header._google_analytics')    
@include('header._head')
</head>
<body>
    @include('header._google_tagmanager')
@include('header._header')
@include('header._menu')
@include('errors._errmsg')

        <!-- Page Heading -->
        <header style="margin:60px 0 50px; background-color: white; padding: 10px 0; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075)">
            <div class="container">
            @hasSection('header')
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @yield('header')
                </h2> 
            @endif
                
            @hasSection('search_form')
                <div class="search-form">
                @yield('search_form')
                </div>                
            @endif
            </div>
        </header>

        <!-- Page Content -->
        <main class="container my-5">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">     
                    <div class="page">
                    @include('layouts.errmsg')
                    @yield('main')
                    </div>
                @hasSection('table_block')
                    @yield('table_block')
                @endif
                </div>
            </div>
        </main>
           
@include('footer._footer')
@include('footer._foot_script')
</body>
</html>
