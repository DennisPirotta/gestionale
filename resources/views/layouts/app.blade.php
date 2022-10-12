<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Gestionale 3D Automation ed SPH Technology">
    <meta name="keywords" content="Gestionale">
    <meta name="author" content="Dennis Pirotta">

    <!-- Android  -->
    <meta name="theme-color" content="#1b1b1b">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- iOS -->
    <meta name="apple-mobile-web-app-title" content="Gestionale">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <!-- Windows  -->
    <meta name="msapplication-navbutton-color" content="#1b1b1b">
    <meta name="msapplication-TileColor" content="#1b1b1b">
    <meta name="msapplication-TileImage" content="{{ asset('images/pwa/icon-128x128.webp') }}">
    <meta name="msapplication-config" content="browserconfig.xml">

    <!-- Pinned Sites  -->
    <meta name="application-name" content="Gestionale">
    <meta name="msapplication-tooltip" content="">
    <meta name="msapplication-starturl" content="/">

    <!-- Tap highlighting  -->
    <meta name="msapplication-tap-highlight" content="no">

    <!-- UC Mobile Browser  -->
    <meta name="browsermode" content="application">

    <!-- Disable night mode for this page  -->
    <meta name="nightmode" content="enable/disable">

    <!-- imagemode - show image even in text only mode  -->
    <meta name="imagemode" content="force">

    <!-- Orientation  -->
    <meta name="screen-orientation" content="portrait">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA -->
    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Nunito" as="font" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" as="font" href="https://fonts.googleapis.com/css?family=Nunito"></noscript>
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css"></noscript>
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.css"></noscript>

    <link rel="icon" sizes="96x96" href="{{asset('images/favicon.png')}}">
    @vite(['resources/js/app.js'])
</head>
<body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous" async></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.js" async></script>
<div id="app">
    @include('components.navbar')
    <main class="py-4">
        @yield('content')
    </main>
</div>
@include('components.flash-messages')
<script src="{{ asset('/sw.js') }}" async></script>
<script>
    if(!navigator.serviceWorker.controller){
        navigator.serviceWorker.register('/sw.js').then(function (reg) {
            console.log("Service Worker has been registered for scope: " + reg.scope)
        })
    }
</script>
</body>
</html>
