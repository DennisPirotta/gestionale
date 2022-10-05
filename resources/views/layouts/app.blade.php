<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Gestionale 3D Automation ed SPH Technology">
    <meta name="keywords" content="Gestionale">
    <meta name="author" content="Dennis Pirotta">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA -->
    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.css">
    <link rel="icon" sizes="96x96" href="{{asset('images/favicon.png')}}">
    @vite(['resources/js/app.js'])
</head>
<body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.js"></script>
<div id="app">
    @include('components.navbar')
    <main class="py-4">
        @yield('content')
    </main>
</div>
@include('components.flash-messages')
<script src="{{ asset('/sw.js') }}"></script>
<script>
    if(!navigator.serviceWorker.controller){
        navigator.serviceWorker.register('/sw.js').then(function (reg) {
            console.log("Service Worker has been registered for scope: " + reg.scope)
        })
    }
</script>
</body>
</html>
