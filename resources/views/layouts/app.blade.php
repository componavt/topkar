<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('header._google_analytics')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TopKar') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
        {!!Html::style('css/font-awesome_5.6.3.css')!!}

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="/css/languages.min.css">
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/forms.css">

        @livewireStyles

        {{ $headExtra ?? "" }}
        
        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased bg-light">
        @include('header._google_tagmanager')
        <x-jet-banner />
        @livewire('navigation-menu')

        <!-- Page Heading -->
        <header class="d-flex py-3 bg-white shadow-sm border-bottom">
            <div class="container">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $header }}
                </h2> 
                
                <div class="search-form">
                {{ $search_form ?? ''}}
                </div>                
            </div>
        </header>

        <!-- Page Content -->
        <main class="container my-5">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">     
                    <div class="page">
@include('layouts.errmsg')
                    {{ $slot }}
                    </div>
                    {{ $table_block ?? '' }}
                </div>
            </div>
        </main>

        @stack('modals')

        @livewireScripts
        {!!Html::script('js/jquery-3.6.0.min.js')!!}
        
        {{ $footScriptExtra ?? "" }}
        
        <script type="text/javascript">
            $(document).ready(function(){
                {{ $jqueryFunc ?? "" }}
            });
        </script>

        
        @stack('scripts')
    </body>
</html>
