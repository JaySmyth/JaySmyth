<!DOCTYPE html>
<html lang="en">
    <head>
        @if(Session::has('token'))<meta http-equiv="refresh" content="1;url={{ url('/label/' . session('token')) }}">@endif
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>IFS Global Logistics</title>

        <!-- Styles -->                
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">           
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        
        @stack('styles')
    </head>  

    <body>        
        <!-- Include the nav bar -->
        @if (Auth::check())
        @include('nav.nav')
        @endif

        <!-- Advanced search modal -->   
        @yield('advanced_search')

        <div class="container-fluid">            
            @yield('content')
        </div>

        <div id="loading">            
            <i class="fas fa-spinner fa-pulse fa-5x fa-fw"></i>
            <span class="sr-only">Loading...</span>
        </div>

        <!-- Flash notification -->
        @include('partials.modals.flash')

        @include('partials.modals.track')

        <!-- to the top -->
        <a id="to_top" title="Back to the top" href="#">
            <span class="fas fa-chevron-up fa-2x text-white" aria-hidden="true"></span>
        </a>

        <!-- Include a polyfill for ES6 Promises (optional) for IE11 and Android browser -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>

        <!-- JavaScripts -->        
        <script src="{{ mix('js/app.js') }}"></script>
        @stack('scripts')

    </body>
</html>
