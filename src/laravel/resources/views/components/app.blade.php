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

    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/js/bootstrap.js'])
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.5.13/dist/vue.global.min.js"></script>
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
    @stack('custom-scripts')
</body>
</html>
