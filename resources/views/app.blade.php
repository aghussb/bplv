<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link id="theme-css" rel="stylesheet" type="text/css" href="{{ asset('/themes/lara-light-indigo/theme.css') }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @inertiaHead
</head>

<body>
    @inertia
</body>

<script> window.laravel_echo_port = '{{env("LARAVEL_ECHO_PORT")}}'; </script>
<script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_PORT')}}/socket.io/socket.io.js"></script>

</html>