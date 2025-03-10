<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <!-- Scripts -->

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>
<body class="d-flex flex-column min-vh-100">
    <div id="app">
        <div class="container">
            <main class="col-12 col-md-12 col-xl-12 bd-content" role="main">
                @include('layouts.navigation')
                <!-- Page Heading -->
                @isset($header)
                    <header class="">
                        <div class="">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <div>
                    {{ $slot }}
                </div>
            </main>
        </div>
        <footer class="mt-auto">
            @isset($footer)

                {{$footer}}

            @endisset
        </footer>
    </div>

</body>
</html>
