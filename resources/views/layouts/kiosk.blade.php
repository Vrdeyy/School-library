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

        /* Animations */
        @keyframes drift {
            0%, 100% { transform: translate(0, 0) rotate(15deg); }
            50% { transform: translate(20px, -20px) rotate(18deg); }
        }
        .animate-drift { animation: drift 10s ease-in-out infinite; }

        @keyframes float-gentle {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(5deg); }
        }
        .animate-float-gentle { animation: float-gentle 15s ease-in-out infinite; }

        /* Effects */
        .sticker-effect {
            filter: 
                drop-shadow(2px 2px 0 white) 
                drop-shadow(-2px -2px 0 white) 
                drop-shadow(2px -2px 0 white) 
                drop-shadow(-2px 2px 0 white)
                drop-shadow(6px 6px 0 rgba(30, 41, 59, 0.15));
        }

        .onomatopoeia {
            font-family: 'Courier Prime', monospace;
            font-weight: 700;
            text-transform: uppercase;
            font-style: italic;
            -webkit-text-stroke: 1.5px #1e293b;
            color: white;
            filter: drop-shadow(5px 5px 0px #9333ea);
            letter-spacing: -0.05em;
        }

        .comic-burst {
            clip-path: polygon(50% 0%, 63% 15%, 95% 10%, 85% 37%, 100% 50%, 85% 63%, 95% 90%, 63% 85%, 50% 100%, 37% 85%, 5% 90%, 15% 63%, 0% 50%, 15% 37%, 5% 10%, 37% 15%);
        }

        .comic-plus {
            clip-path: polygon(35% 0%, 65% 0%, 65% 35%, 100% 35%, 100% 65%, 65% 65%, 65% 100%, 35% 100%, 35% 65%, 0% 65%, 0% 35%, 35% 35%);
        }

        .panel-border {
            border: 4px solid #1e293b;
            box-shadow: 8px 8px 0px #1e293b;
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

            <!-- FLOATING PLUS SIGNS & DECOR (Subtle) -->
            <div class="absolute top-[20%] right-[30%] opacity-[0.1] text-slate-400 font-bold text-4xl animate-float-gentle">+</div>
            <div class="absolute bottom-[40%] left-[25%] opacity-[0.08] text-purple-400 font-bold text-5xl animate-bounce" style="animation-duration: 4s">+</div>
            
            <!-- PREMIUM STICKER SHAPES (Backdrops) -->
            <div class="absolute top-[5%] right-[15%] w-32 h-32 bg-purple-100/20 comic-burst -rotate-12 opacity-[0.1] animate-pulse"></div>
            <div class="absolute bottom-[10%] left-[10%] w-40 h-40 bg-slate-200/20 comic-star rotate-12 opacity-[0.08] animate-float-gentle"></div>

            <!-- BACKGROUND ICON STICKERS (Scaled & Layered Above Shapes) -->
            <!-- 1. Book (Library) -->
            <div class="absolute top-[8%] left-[-4%] sm:left-[10%] rotate-[35deg] z-20 animate-float-gentle opacity-[0.2] sticker-effect">
                <svg class="w-32 h-32 sm:w-56 sm:h-56 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <!-- Plus Decor near book -->
            <div class="absolute top-[20%] left-[20%] opacity-[0.15] text-slate-400 font-bold text-3xl z-20 animate-pulse">+</div>

            <!-- 2. Calculator (Math) -->
            <div class="absolute top-[8%] right-[8%] rotate-[12deg] z-20 animate-pulse opacity-[0.15] sticker-effect">
                <svg class="w-20 h-20 sm:w-40 sm:h-40 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-3-2.25V18M10.5 15.75V18M15.75 12V13.5m-3-1.5V13.5m-3-1.5V13.5M6.75 6.75h10.5a.75.75 0 01.75.75v10.5a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V7.5a.75.75 0 01.75-.75z" />
                </svg>
            </div>

            <!-- 3. Globe (Sociology/General) -->
            <div class="absolute bottom-[10%] left-[2%] sm:left-[8%] rotate-[-15deg] z-20 animate-bounce opacity-[0.2] sticker-effect" style="animation-duration: 9s">
                <svg class="w-32 h-32 sm:w-64 sm:h-64 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A11.952 11.952 0 0112 15c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 013 12c0-.778.099-1.533.284-2.253" />
                </svg>
            </div>
            <!-- Plus near globe -->
            <div class="absolute bottom-[15%] left-[25%] opacity-[0.1] text-purple-400 font-extrabold text-4xl z-20 animate-float-gentle">+</div>

            <!-- 4. Graduation Cap (Education) -->
            <div class="absolute bottom-[30%] right-[10%] rotate-[-10deg] z-20 animate-float-gentle opacity-[0.2] sticker-effect">
                <svg class="w-32 h-32 sm:w-64 sm:h-64 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
            </div>

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
