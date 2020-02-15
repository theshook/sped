<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    @yield('link')
</head>
<body>
    <div id="app">
        @auth
            <div id="wrapper">
                @include('partials.sidebar')

                <div id="content-wrapper" class="d-flex flex-column">
                    <div id="content">
                        @include('partials.navbar')
                        <div class="container-fluid">   
                            @yield('content')
                        </div>
                    </div>
                    @include('partials.footer')
                </div>
            </div>
        @else
            <main class="py-4">
                <div class="container">
                    @yield('content')
                </div>
            </main>
        @endauth
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script> 
    <script src="{{ asset('js/jquery.easing.min.js') }}"></script>
    {{-- <script src="{{ asset('js/app.js') }}" ></script> --}}
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    @yield('js')
</body>
</html>
