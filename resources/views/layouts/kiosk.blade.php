<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kiosk Perpustakaan</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Scanner & Alpine -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Outfit', sans-serif; }

        .comic-halftone {
            background-image: radial-gradient(#1e293b 15%, transparent 16%);
            background-size: 12px 12px;
            opacity: 0.1;
        }

        .screentone {
            background-image: radial-gradient(circle, #1e293b 1px, transparent 0);
            background-size: 8px 8px;
            opacity: 0.05;
        }

        .comic-hatch {
            background: repeating-linear-gradient(
                -45deg,
                transparent,
                transparent 5px,
                rgba(30, 41, 59, 0.05) 5px,
                rgba(30, 41, 59, 0.05) 6px
            );
        }

        .paper-texture {
            background-color: #fdfcf9;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            background-blend-mode: overlay;
        }

        @keyframes float-gentle {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -20px) rotate(2deg); }
            66% { transform: translate(-20px, 10px) rotate(-2deg); }
        }
        .animate-float-gentle { animation: float-gentle 12s ease-in-out infinite; }
        
        @keyframes drift {
            0% { transform: rotate(0deg) translate(80px) rotate(0deg); }
            100% { transform: rotate(360deg) translate(80px) rotate(-360deg); }
        }
        .animate-drift { animation: drift 40s linear infinite; }

        .mesh-blob {
            filter: blur(60px);
            opacity: 0.1;
            mix-blend-mode: multiply;
        }

        .comic-star {
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
        }

        .comic-burst {
            clip-path: polygon(50% 0%, 63% 15%, 95% 10%, 85% 37%, 100% 50%, 85% 63%, 95% 90%, 63% 85%, 50% 100%, 37% 85%, 5% 90%, 15% 63%, 0% 50%, 15% 37%, 5% 10%, 37% 15%);
        }

        .burst-border {
            filter: drop-shadow(0 0 1px #1e293b) drop-shadow(0 0 1px #1e293b) drop-shadow(0 0 1px #1e293b);
        }

        .sticker-effect {
            filter: 
                drop-shadow(2px 2px 0 white) 
                drop-shadow(-2px -2px 0 white) 
                drop-shadow(2px -2px 0 white) 
                drop-shadow(-2px 2px 0 white)
                drop-shadow(4px 4px 0 rgba(30, 41, 59, 0.1));
        }

        .speed-lines {
            background-image: repeating-conic-gradient(
                from 0deg at 50% 50%,
                transparent 0deg,
                transparent 2deg,
                rgba(37, 99, 235, 0.03) 2deg,
                rgba(37, 99, 235, 0.03) 3deg
            );
        }

        .onomatopoeia {
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            text-transform: uppercase;
            font-style: italic;
            -webkit-text-stroke: 1.5px #1e293b;
            color: white;
            filter: drop-shadow(3px 3px 0px #2563eb);
            letter-spacing: -0.05em;
        }

        .chromatic-offset {
            text-shadow: 
                -1px -1px 0 rgba(255,0,0,0.4),
                1px 1px 0 rgba(0,255,255,0.4);
        }

        @media (min-width: 640px) {
            .onomatopoeia {
                -webkit-text-stroke: 2px #1e293b;
                filter: drop-shadow(4px 4px 0px #2563eb);
            }
            .chromatic-offset {
                text-shadow: 
                    -2px -2px 0 rgba(255,0,0,0.5),
                    2px 2px 0 rgba(0,255,255,0.5);
            }
        }

        .zig-zag {
            background: linear-gradient(135deg, #1e293b 25%, transparent 25%),
                        linear-gradient(225deg, #1e293b 25%, transparent 25%),
                        linear-gradient(45deg, #1e293b 25%, transparent 25%),
                        linear-gradient(315deg, #1e293b 25%, transparent 25%);
            background-position: 10px 0, 10px 0, 0 0, 0 0;
            background-size: 20px 20px;
            background-repeat: repeat-x;
        }

        .ink-splat {
            mask-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M160.4,-163.2C194.2,-132.8,200.2,-71.4,188.7,-17.1C177.2,37.2,148.2,84.4,109.4,118.8C70.6,153.2,22,174.8,-27.1,171.2C-76.2,167.6,-125.8,138.8,-153.4,96.8C-181,54.8,-186.6,-0.4,-171.4,-48.2C-156.2,-96,-120.2,-136.4,-78.6,-164.7C-37,-193,10.2,-209.2,65.3,-205.9C120.4,-202.6,126.6,-193.6,160.4,-163.2Z' transform='translate(100 100)' /%3E%3C/svg%3E");
            mask-size: contain;
            mask-repeat: no-repeat;
        }

        .panel-border {
            border: 3px solid #1e293b;
            box-shadow: 6px 6px 0px #1e293b;
        }

        @media (min-width: 640px) {
            .panel-border {
                border-width: 4px;
                box-shadow: 8px 8px 0px #1e293b;
            }
        }

        .benday-dots {
            background-image: radial-gradient(#1e293b 20%, transparent 20%);
            background-size: 4px 4px;
        }

        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100vh); }
        }
        .scanline {
            width: 100%;
            height: 100px;
            z-index: 5;
            background: linear-gradient(to bottom, transparent, rgba(37, 99, 235, 0.05), transparent);
            animation: scanline 10s linear infinite;
        }
    </style>
</head>
<body class="min-h-full text-slate-900 antialiased font-sans paper-texture">
    <div class="min-h-screen py-4 sm:py-8 px-4 sm:px-6 relative flex flex-col overflow-x-hidden">
        
        <!-- Background Elements (Synchronized with Catalog Index) -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0 bg-slate-50">
            <!-- Layer 0: Paper Texture & Comic Halftone Global Texture -->
            <div class="absolute inset-0 z-0 paper-texture opacity-20"></div>
            <div class="absolute inset-0 z-0 comic-halftone"></div>
            <div class="absolute inset-0 z-0 screentone"></div>
            
            <!-- Layer 1: Ink Splashes (Vibrant Blobs) -->
            <div class="absolute inset-0 z-[1] mix-blend-multiply">
                <div class="absolute top-[-5%] left-[-10%] w-[400px] sm:w-[600px] h-[400px] sm:h-[600px] bg-blue-500 mesh-blob animate-drift"></div>
                <div class="absolute bottom-[-5%] right-[-10%] w-[500px] sm:w-[700px] h-[500px] sm:h-[700px] bg-indigo-500 mesh-blob animate-drift" style="animation-delay: -15s"></div>
                <div class="absolute top-[40%] right-[20%] w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] bg-purple-500 mesh-blob animate-drift" style="animation-delay: -25s"></div>
            </div>

            <!-- Layer 2: Comic Hatching Corners & Speed Lines -->
            <div class="absolute inset-0 z-[2] speed-lines opacity-100"></div>
            <div class="absolute top-0 right-0 w-[60%] sm:w-[40%] h-[30%] sm:h-[40%] z-[2] comic-hatch"></div>
            <div class="absolute bottom-0 left-0 w-[70%] sm:w-[50%] h-[20%] sm:h-[30%] z-[2] comic-hatch opacity-60"></div>

            <!-- Layer 3: Pop-Art Bursts (Stars) -->
            <div class="absolute top-[5%] sm:top-[10%] right-[10%] sm:right-[15%] w-32 sm:w-64 h-32 sm:h-64 bg-indigo-100/40 comic-burst z-[3] rotate-12 animate-pulse"></div>
            <div class="absolute bottom-[15%] sm:bottom-[20%] left-[2%] sm:left-[5%] w-24 sm:w-48 h-24 sm:h-48 bg-purple-100/40 comic-burst z-[3] -rotate-12 animate-bounce" style="animation-delay: 2s"></div>
            <div class="absolute top-[35%] left-[5%] w-20 sm:w-32 h-20 sm:h-32 bg-blue-100/30 comic-star z-[3] rotate-45 animate-float-gentle"></div>
            <div class="absolute bottom-[45%] right-[5%] w-24 sm:w-40 h-24 sm:h-40 bg-pink-100/30 comic-burst z-[3] -rotate-45 animate-pulse" style="animation-delay: 1.5s"></div>

            <!-- Layer 4: Tech Grid & Lines (Offset Comic Style) -->
            <svg class="absolute inset-0 opacity-[0.15] sm:opacity-[0.25] text-blue-900 z-[4] tech-grid" fill="none" stroke="currentColor" viewBox="0 0 100 100">
                <defs>
                    <pattern id="comicGrid" width="20" height="20" patternUnits="userSpaceOnUse">
                        <path d="M 20 0 L 0 0 0 20" fill="none" stroke="currentColor" stroke-width="0.2"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#comicGrid)" />
            </svg>

            <!-- Layer 5: Silicon/Circuit Lines (Bold Comic Lines) -->
            <svg class="absolute inset-0 opacity-[0.2] sm:opacity-[0.35] text-blue-700 z-[5]" viewBox="0 0 1000 1000">
                <path d="M0 150 H300 L350 200 H700 L750 250 H1000" fill="none" stroke="currentColor" stroke-width="4" stroke-dasharray="10 10" />
                <path d="M1000 750 H750 L700 800 H350 L300 850 H0" fill="none" stroke="currentColor" stroke-width="3" />
            </svg>

            <!-- Layer 6: Silhouetted Library Icons (Sticker Style) -->
            <div class="absolute inset-0 z-[6] text-blue-900/10 sm:text-blue-900/20">
                <svg class="absolute top-[10%] sm:top-[15%] left-[5%] sm:left-[8%] w-24 sm:w-40 h-24 sm:h-40 rotate-12 animate-float-gentle sticker-effect" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5zM6 12v5c0 2 2 3 6 3s6-1 6-3v-5" />
                </svg>
                <svg class="absolute bottom-[20%] sm:bottom-[25%] right-[5%] sm:right-[10%] w-32 sm:w-56 h-32 sm:h-56 -rotate-12 animate-float-gentle sticker-effect" style="animation-delay: -5s" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M4 6h16v12H4zm2 2v8h12V8zm2 2h8v2H8zm0 4h8v2H8z" />
                </svg>
            </div>

            <!-- Layer 7: Onomatopoeia -->
            <div class="absolute inset-0 z-[7] pointer-events-none overflow-hidden">
                <div class="absolute top-[8%] left-[8%] sm:top-[10%] sm:left-[10%] animate-bounce" style="animation-duration: 4s">
                    <span class="onomatopoeia text-xl sm:text-4xl -rotate-12">SMASH!</span>
                </div>
                <div class="absolute bottom-[12%] right-[4%] sm:bottom-[20%] sm:right-[5%] animate-pulse">
                    <span class="onomatopoeia text-lg sm:text-3xl rotate-12" style="filter: drop-shadow(4px 4px 0px #4f46e5);">CRUSH!</span>
                </div>
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
                <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                <span>©   COPYRIGHT • {{ date('Y') }} •</span>
                <span class="h-4 w-px bg-slate-200 mx-1"></span>
                <span> Kampunk Dev Team</span>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
