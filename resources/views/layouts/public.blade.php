<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Pusvetma LCS') }}</title>

        <!-- PWA / Web App Manifest -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta name="theme-color" content="#1e40af">
        <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen">
            <!-- Top bar -->
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <a href="/" class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                    <div class="w-8 h-8 bg-blue-600 text-white flex items-center justify-center rounded font-bold">V</div>
                                    Pusvetma LCS
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @isset($header)
                        <header class="mb-6">
                            <div class="px-2">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset
                    {{ $slot }}
                </div>
            </main>
        </div>

        <!-- Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('{{ asset('sw.js') }}')
                        .then(registration => {
                            console.log('ServiceWorker registration successful with scope: ', registration.scope);
                        })
                        .catch(error => {
                            console.log('ServiceWorker registration failed: ', error);
                        });
                });
            }
        </script>
    </body>
</html>
