<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="user" content="{{ Auth::user() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('title')
</head>

<body>
    @yield('content')
</body>
</html>
