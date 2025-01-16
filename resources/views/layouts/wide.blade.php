<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
@include('header._google_analytics')    
@include('header._head')
@yield('head_master')
</head>
<body>
    @include('header._google_tagmanager')
    @yield('modals')
    <header id="header">
        <div class="container">
            <a href="/"><img id="logo" src="/images/logo_{{ app()->getLocale() }}.png"></a>
            @include('header._left_menu')
            @include('header._right_menu')
        </div>
    </header>

    <div>
        <!-- Page Heading -->
    
    @hasSection('header')
        <h1>@yield('header')</h1> 
    @endif

    @include('errors._errmsg')
    
    @hasSection('search_form')
        <div class="blank">
            @yield('search_form')
        </div>                
    @endif

        <!-- Page Content -->
    @hasSection('buttons')
    <div class="b-buttons">
        @yield('buttons')
    </div>
    @endif
        
    @hasSection('table_block')
    <div class="table-block">
        @yield('table_block')
    </div>
    @endif
                
    @hasSection('main')
        <div class="blank">
            @yield('main')
        </div>                
    @endif
    </div>
           
@include('footer._footer')
@include('footer._foot_script')
</body>
</html>
