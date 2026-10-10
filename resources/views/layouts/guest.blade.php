<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Halim Center') }} - Login Klinik</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-hfc-body antialiased bg-hfc-bg selection:bg-hfc-primary selection:text-white">
        <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
            <!-- Background Decorative Elements -->
            <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-hfc-primary/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-hfc-primary/15 blur-3xl pointer-events-none"></div>

            <!-- Header Logo -->
            <div class="mb-8 transform hover:scale-105 transition duration-300">
                <a href="/">
                    <x-application-logo />
                </a>
            </div>

            <!-- Auth Form Card -->
            <div class="w-full sm:max-w-md bg-white/90 backdrop-blur-md rounded-3xl shadow-xl shadow-hfc-primary/5 border border-hfc-primary/10 p-8 sm:p-10 relative z-10">
                {{ $slot }}
            </div>

            <!-- Footer Note -->
            <div class="mt-8 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} <span class="font-semibold text-hfc-dark">Halim Center</span>. All rights reserved.
            </div>
        </div>
    </body>
</html>
