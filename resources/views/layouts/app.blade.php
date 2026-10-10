<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Klinik') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Google Material Symbols Icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-100">
        <div x-data="{ sidebarOpen: false }" class="flex min-h-screen">

            <!-- Left Sidebar -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 overflow-x-hidden">
                
                <!-- Top Header Bar -->
                <header class="bg-white border-b border-gray-100 sticky top-0 z-30 h-16 flex items-center">
                    <div class="w-full flex items-center justify-between px-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <!-- Mobile Menu Toggle Button -->
                            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 lg:hidden">
                                <span class="material-symbols-outlined text-2xl">menu</span>
                            </button>

                            @isset($header)
                                {{ $header }}
                            @endisset
                        </div>
                    </div>
                </header>

                <!-- Main Page Slot -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>

        </div>
    </body>
</html>
