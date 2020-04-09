<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="user" content="{{ Auth::user() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('title')
    <!-- Fonts -->
	  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/client-styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/fontawesome-5.11/css/all.min.css') }}" rel="stylesheet" type="text/css">
    @yield('link')
    @yield('style')
</head>

<body>
    <div class="container-fluid px-0" id="app">
      @include('partials.navbar-client')
      <div class="container pt-3 mt-4">
        @yield('content')
      </div>

      <footer class="py-2 mt-5">

      </footer>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('plugins/fontawesome-5.11/js/all.min.js') }}"></script>
    @yield('scripts')
</body>

</html>
