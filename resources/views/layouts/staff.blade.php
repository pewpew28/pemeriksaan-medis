<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ 'HealthCare+' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('style')
</head>

<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: false }">
    @auth
        @if (Auth::user()->role !== 'pasien')
            <!-- Sidebar for staff -->
            @include('layouts.sidebar')

            <!-- Main Content with sidebar margin -->
            <div class="lg:ml-80 min-h-screen">
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="p-6">
                    {{ $slot }}
                </main>
            </div>
        @else
            <!-- Regular navbar for patients -->
            @include('layouts.navigation')

            <!-- Main Content without sidebar -->
            <div class="min-h-screen">
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main>
                    {{ $slot }}
                </main>
            </div>
        @endif
    @else
        <!-- Guest layout -->
        <div class="min-h-screen">
            {{ $slot }}
        </div>
    @endauth
    @stack('scripts')
</body>

</html>
