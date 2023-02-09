<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="/favicon.png">
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

{{--        <link href="/css/please-wait.css" rel="stylesheet">--}}
{{--        <link href="/css/spinkit.min.css" rel="stylesheet">--}}

        <link href="/css/output.css" rel="stylesheet">
        <link href="/css/custom.css" rel="stylesheet">

    </head>
    <body class="font-sans antialiased">
{{--    <div class="inner" ng-view></div>--}}
{{--    <script type="text/javascript" src="/js/please-wait.min.js"></script>--}}
{{--        <script type="text/javascript">--}}
{{--            window.loading_screen = window.pleaseWait({--}}
{{--                logo: "favicon.png",--}}
{{--                backgroundColor: '#000',--}}
{{--                loadingHtml: '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>',--}}
{{--            });--}}
{{--        </script>--}}


    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
{{--            @include('layouts.navigation')--}}

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            @include('layouts.footer')
        </div>

{{--        <script>window.loading_screen.finish();</script>--}}

        <script src="/js/script.js"></script>

    </body>
</html>
