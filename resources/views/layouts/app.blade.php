<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name', 'Resume Builder') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex flex-col">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-200 bg-white">
            <div class="max-w-7xl mx-auto px-6 py-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                    <!-- Brand -->
                    <div>
                        <div class="text-sm font-semibold text-gray-900">
                            {{ config('app.name', 'Resume Builder') }}
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            Build professional resumes fast. Export clean PDF.
                        </p>
                    </div>

                    <!-- Links -->
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">
                            Home
                        </a>
                        <a href="{{ route('templates.index') }}" class="text-gray-600 hover:text-gray-900">
                            Templates
                        </a>

                        <a href="#" class="text-gray-600 hover:text-gray-900">
                            Privacy
                        </a>
                        <a href="#" class="text-gray-600 hover:text-gray-900">
                            Terms
                        </a>
                    </div>
                </div>

                <div class="mt-6 text-xs text-gray-400">
                    © {{ date('Y') }} {{ config('app.name', 'Resume Builder') }}. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
</body>

</html>
