<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- PWA / Web App Manifest -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta name="theme-color" content="#1e40af">
        <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-[conic-gradient(at_top_right,_var(--tw-gradient-stops))] from-blue-900 via-indigo-800 to-blue-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            <!-- Decorative blobs -->
            <div class="absolute top-0 left-0 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-0 right-0 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

            <div class="z-10 w-[92%] sm:w-full max-w-md px-6 sm:px-8 py-10 bg-white/95 backdrop-blur-xl shadow-2xl overflow-hidden rounded-3xl transition-all duration-300 mt-4 sm:mt-0">
                <div class="text-center mb-8">
                    <a href="/" class="flex flex-col items-center gap-4">
                        <img src="{{ asset('images/logo_pusvetma.png') }}" alt="Logo Pusvetma" class="h-20 w-auto object-contain drop-shadow-md" />
                        <div>
                            <h1 class="text-3xl text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-indigo-700 tracking-tight"><span class="font-medium">Pusvetma</span> <span class="font-extrabold">LCS</span></h1>
                            <p class="text-gray-500 text-sm font-medium tracking-wide mt-1">Like, Comment, Share</p>
                        </div>
                    </a>
                </div>
                
                {{ $slot }}
            </div>

            <!-- Copyright Footer -->
            <div class="z-10 mt-3 text-center">
                <p class="text-blue-200 text-sm font-medium tracking-wide">Pusvetma LCS System &copy; {{ date('Y') }}</p>
            </div>
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
