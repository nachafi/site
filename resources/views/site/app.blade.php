<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('settings.site_title') }}</title>
    @include('site.partials.styles')
</head>
<body>
@include('site.partials.header')
<main class="app-content" id="app">
        @yield('content')
    </main>
@include('site.partials.footer')
@include('site.partials.scripts')
</body>
</html>