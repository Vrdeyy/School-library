<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kiosk Perpustakaan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js & HTML5-QRCode -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <!-- Alpine is loaded via Vite usually, but ensuring it's available -->
</head>
<body class="h-full overflow-hidden text-white antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center p-6 relative">
        
        <!-- Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] bg-blue-600/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-[20%] -right-[10%] w-[50%] h-[50%] bg-purple-600/20 rounded-full blur-3xl"></div>
        </div>

        <!-- Main Content -->
        <main class="w-full max-w-4xl relative z-10">
            @yield('content')
        </main>

        <!-- Footer / Status -->
        <footer class="absolute bottom-6 text-sm text-gray-500">
            PerpusDigital Kiosk v1.0
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
