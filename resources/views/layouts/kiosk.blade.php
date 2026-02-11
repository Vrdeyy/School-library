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
<body class="min-h-full bg-slate-50 text-slate-900 antialiased font-sans">
    <div class="min-h-screen py-8 px-4 sm:px-6 relative flex flex-col overflow-x-hidden">
        
        <!-- Background Decorative Elements (Light User Card Style) -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <!-- Subtle Global Texture -->
            <div class="absolute inset-0 opacity-[0.08]" 
                 style="background-image: repeating-linear-gradient(135deg, #475569 0, #475569 0.5px, transparent 0.5px, transparent 15px);"></div>

            <!-- Accent Blocks -->
            <div class="absolute -top-[5%] -left-[5%] w-[40%] h-[40%] bg-blue-100/40 rounded-full blur-[100px]"></div>
            <div class="absolute -bottom-[5%] -right-[5%] w-[40%] h-[40%] bg-indigo-100/40 rounded-full blur-[100px]"></div>
            
            <!-- Tech Grid (Light) -->
            <div class="absolute inset-0 opacity-[0.05]" 
                 style="background-image: linear-gradient(to right, #1e293b 1px, transparent 1px), linear-gradient(to bottom, #1e293b 1px, transparent 1px); background-size: 30px 30px;"></div>
            
            <!-- Geometric Icons Watermark (Light) -->
            <svg class="absolute right-[5%] top-[10%] w-64 h-64 opacity-[0.04] text-blue-600 rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
        </div>

        <!-- Main Content -->
        <main class="w-full max-w-7xl mx-auto relative z-10 flex-grow flex flex-col">
            @yield('content')
        </main>

        <!-- Dynamic Status Footer -->
        <footer class="relative z-10 py-8 text-center">
            <div class="inline-flex items-center gap-4 px-6 py-2.5 rounded-2xl bg-white border border-slate-200 shadow-xl text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase">
                <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                <span>SYSTEM_OPERATIONAL_SECURE</span>
                <span class="h-4 w-px bg-slate-200 mx-1"></span>
                <span>TERMINAL_v5.0_PRO</span>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
