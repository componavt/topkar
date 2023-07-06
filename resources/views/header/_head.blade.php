        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            @hasSection('headTitle')
                @yield('headTitle') &mdash; 
            @endif
            
            {{ config('app.name', 'TopKar') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
        {!!Html::style('css/font-awesome_5.6.3.css')!!}

        <!-- Styles -->
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/languages.min.css">
        <link rel="stylesheet" href="/css/navigation.css">
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/forms.css">
        
@yield('headExtra')
    
    
