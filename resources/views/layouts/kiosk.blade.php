<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kiosk Perpustakaan</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Scanner & Alpine -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        * { font-family: 'Courier Prime', monospace !important; }

        /* Global Pattern Styles */
        .bg-texture {
            background-image: repeating-linear-gradient(135deg, #1e293b 0, #1e293b 0.8px, transparent 0.8px, transparent 20px);
            opacity: 0.25;
            mask-image: linear-gradient(to bottom, white, transparent);
        }

        .comic-halftone {
            background-image: radial-gradient(#1e293b 15%, transparent 16%);
            background-size: 15px 15px;
            opacity: 0.1;
        }

        .benday-dots {
            background-image: radial-gradient(#1e293b 25%, transparent 25%);
            background-size: 5px 5px;
        }

        .screentone {
            background-image: radial-gradient(circle, #1e293b 1px, transparent 0);
            background-size: 8px 8px;
            opacity: 0.05;
        }

        .tech-grid {
            background-image: 
                linear-gradient(to right, rgba(147, 51, 234, 0.15) 1.5px, transparent 1.5px),
                linear-gradient(to bottom, rgba(147, 51, 234, 0.15) 1.5px, transparent 1.5px);
            background-size: 60px 60px;
        }

        .paper-texture {
            background-color: #fdfcf9;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            background-blend-mode: overlay;
            opacity: 0.95;
        }

        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100vh); }
        }
        .scanline {
            width: 100%;
            height: 100px;
            z-index: 5;
            background: linear-gradient(to bottom, transparent, rgba(147, 51, 234, 0.1), transparent);
            animation: scanline 10s linear infinite;
        }
    </style>
</head>
<body class="min-h-full text-slate-900 antialiased font-sans paper-texture">
    <div class="min-h-screen py-4 sm:py-8 px-4 sm:px-6 relative flex flex-col overflow-x-hidden">
        
        <!-- Background Elements (Synchronized with Catalog Index) -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden bg-slate-50">
            <!-- GLOBAL TEXTURES -->
            <div class="absolute inset-0 z-0 comic-halftone opacity-[0.06]"></div>
            <div class="absolute inset-0 z-0 bg-texture"></div>
            <div class="absolute inset-0 z-0 tech-grid opacity-[0.6]"></div>

            <!-- Middle Right Accent Block -->
            <div class="absolute right-[-5%] top-[40%] w-[300px] h-[600px] bg-violet-100/40 -rotate-[12deg] border-r-[4px] border-violet-200/50"></div>

            <!-- CIRCUIT LINES (Bold Comic Style) -->
            <svg class="absolute inset-0 w-full h-full opacity-[0.4] text-purple-600" viewBox="0 0 1000 1000">
                <path d="M0 100 H200 L250 150 H500 L550 100 H1000" fill="none" stroke="currentColor" stroke-width="2" />
                <path d="M1000 850 H750 L700 900 H300 L250 850 H0" fill="none" stroke="currentColor" stroke-width="1.5" />
                <path d="M200 0 V150 L150 200 V500" fill="none" stroke="currentColor" stroke-width="1.2" stroke-dasharray="10 10" />
                <circle cx="250" cy="150" r="4" fill="currentColor" />
                <circle cx="700" cy="900" r="3" fill="currentColor" />
            </svg>

            <div class="scanline z-[10]"></div>
        </div>

        <!-- Main Content -->
        <main class="w-full max-w-7xl mx-auto relative z-10 flex-grow flex flex-col">
            @yield('content')
        </main>

        <!-- Dynamic Status Footer -->
        <footer class="relative z-10 py-8 text-center">
            <div class="inline-flex items-center gap-4 px-6 py-2.5 rounded-2xl bg-white border border-slate-200 shadow-xl text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase">
                <span class="flex h-2 w-2 rounded-full bg-purple-600 animate-pulse"></span>
                <span>© Hak Cipta • {{ date('Y') }} •</span>
                <span class="h-4 w-px bg-slate-200 mx-1"></span>
                <span> Kampunk Dev Team</span>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
