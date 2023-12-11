<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="d-flex">
    <!-- Include the sidebar -->
    @include('layouts.adminSidebar')

    <!-- Main content area -->
    <div id="app" class="flex-grow-1">
        <main class="py-4">
            @yield('content')
        </main>
        <!-- Include the footer -->
        @include('layouts.footer')
    </div>
</body>

</html>
