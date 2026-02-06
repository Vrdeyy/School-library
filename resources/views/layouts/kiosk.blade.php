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
    
    <!-- Scanner & Alpine -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-full bg-[#05060f] text-gray-200 antialiased font-sans">
    <div class="min-h-screen py-8 px-4 sm:px-6 relative flex flex-col overflow-x-hidden">
        
        <!-- Background Decorative Elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-[10%] -left-[10%] w-[60%] h-[60%] bg-primary-600/10 rounded-full blur-[120px] animate-pulse-glow"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[60%] h-[60%] bg-accent-600/10 rounded-full blur-[120px] animate-pulse-glow" style="animation-delay: -4s"></div>
            
            <!-- Grid Pattern -->
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:40px_40px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>
        </div>

        <!-- Main Content -->
        <main class="w-full max-w-7xl mx-auto relative z-10 flex-grow flex flex-col">
            @yield('content')
        </main>

        <!-- Dynamic Status Footer -->
        <footer class="relative z-10 py-8 text-center">
            <div class="inline-flex items-center gap-4 px-6 py-2 rounded-full glass-card border-white/5 text-xs font-medium tracking-widest text-gray-500 uppercase">
                <span class="flex h-2 w-2 rounded-full bg-neon-green animate-pulse"></span>
                <span>System Operational</span>
                <span class="h-4 w-px bg-white/10 mx-1"></span>
                <span>v4.0.0-GOLD</span>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
