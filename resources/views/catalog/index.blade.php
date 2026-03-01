<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Perpustakaan | SMK YAJ DEPOK</title>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Courier Prime"', 'monospace'],
                        mono: ['"Courier Prime"', 'monospace'],
                    }
                }
            }
        }
    </script>
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

        /* Removed chromatic-offset */

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

        /* Street Authenticator Elements */
        .holographic-shimmer {
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0) 0%, 
                rgba(255, 255, 255, 0.8) 50%, 
                rgba(255, 255, 255, 0) 100%);
            background-size: 200% 200%;
            animation: shimmer 3s infinite linear;
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .tape-effect {
            position: absolute;
            width: 60px;
            height: 25px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(2px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            z-index: 30;
            transform: rotate(-45deg);
            pointer-events: none;
        }

        .security-stamp {
            position: absolute;
            font-weight: 900;
            padding: 4px 12px;
            border: 4px solid currentColor;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            opacity: 0.15;
            user-select: none;
            pointer-events: none;
            z-index: 5;
        }

        .perforated-divider {
            position: relative;
            border-top: 4px dashed #1e293b;
        }
        @media (min-width: 640px) {
            .perforated-divider {
                border-top: 0;
                border-left: 4px dashed #1e293b;
            }
        }
        .perforated-divider::before,
        .perforated-divider::after {
            content: '';
            position: absolute;
            width: 24px;
            height: 24px;
            background-color: #f8fafc; /* Matches body bg */
            border: 4px solid #1e293b;
            border-radius: 50%;
            z-index: 20;
        }
        /* Mobile: Top-Left and Top-Right */
        .perforated-divider::before { top: -14px; left: -14px; }
        .perforated-divider::after { top: -14px; right: -14px; }

        /* Desktop: Top-Left and Bottom-Left */
        @media (min-width: 640px) {
            .perforated-divider::before { top: -14px; left: -14px; }
            .perforated-divider::after { top: auto; bottom: -14px; left: -14px; right: auto; }
        }

        [x-cloak] { display: none !important; }
        body { font-family: 'Courier Prime', monospace; background-color: #f8fafc; }
    </style>
</head>
<body class="text-slate-900 min-h-screen overflow-x-hidden selection:bg-purple-600 selection:text-white paper-texture">
    <div class="grain"></div>
    <!-- Background Elements (Comic/Pop-Art Layered Version) -->
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

        <!-- 5. Ticket (System Theme) -->
        <div class="absolute top-[45%] left-[45%] rotate-[15deg] z-10 opacity-[0.1] animate-pulse sticker-effect">
            <svg class="w-12 h-12 sm:w-18 sm:h-18 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6V3.25c0-.69-.56-1.25-1.25-1.25H8.75c-.69 0-1.25.56-1.25 1.25V6m8.5 0h.75c.69 0 1.25.56 1.25 1.25v12.5c0 .69-.56 1.25-1.25 1.25H4.25c-.69 0-1.25-.56-1.25-1.25V7.25c0-.69.56-1.25 1.25-1.25h.75m8.5 0V6a2.25 2.25 0 0 0-4.5 0v1.5m4.5 0h-4.5" />
            </svg>
        </div>

        <!-- 6. Sparkles/Stars (Visual Decor) -->
        <div class="absolute top-[50%] right-[8%] rotate-[25deg] z-10 opacity-[0.12] animate-bounce sticker-effect">
            <svg class="w-10 h-10 sm:w-16 sm:h-16 text-purple-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
            </svg>
        </div>

    </div>
    </div>

    <div class="relative z-10">
        <!-- Header/Hero -->
        <header class="pt-16 sm:pt-24 pb-12 sm:pb-16 px-4 sm:px-6 text-center max-w-5xl mx-auto">
            <div class="inline-flex items-center gap-3 px-4 sm:px-6 py-2 rounded-full bg-white border-2 border-slate-900 shadow-[4px_4px_0px_#9333ea] mb-8 sm:mb-12 relative">
                <span class="flex h-2 w-2 rounded-full bg-purple-600 animate-pulse"></span>
                <span class="text-[8px] sm:text-[10px] font-bold tracking-[0.1em] sm:tracking-[0.2em] text-slate-900">Katalog_Digital_Resmi</span>
            </div>

            <h1 class="text-4xl sm:text-6xl lg:text-8xl font-bold text-slate-900 tracking-tight leading-none mb-6 sm:8 relative">
                Jelajahi<br>
                <span class="text-purple-600">Wawasan.</span>
                <!-- Floating accent comic stars -->
                <div class="absolute -top-4 sm:-top-6 left-0 flex gap-1 sm:gap-2">
                    <div class="w-3 h-3 sm:w-4 sm:h-4 bg-purple-600 comic-plus border-2 border-slate-900 shadow-[2px_2px_0px_white] rotate-12"></div>
                    <div class="w-3 h-3 sm:w-4 sm:h-4 bg-violet-600 comic-plus border-2 border-slate-900 shadow-[2px_2px_0px_white] -rotate-12"></div>
                </div>
            </h1>
            
            <p class="text-slate-500 text-sm sm:text-lg font-bold tracking-[0.1em] sm:tracking-[0.2em] uppercase italic max-w-2xl mx-auto mb-10 sm:16 -mt-2">
                Telusuri koleksi sumber daya akademik premium kami.
            </p>

            <div class="flex justify-center gap-6 sm:gap-12 flex-wrap mb-12 sm:20">
                <div class="text-center group">
                    <p class="text-3xl sm:text-5xl font-bold text-slate-900 group-hover:text-purple-600 transition-colors italic">0{{ $books->count() }}</p>
                    <p class="text-[8px] sm:text-[10px] font-bold text-slate-400 tracking-[0.1em] sm:tracking-[0.2em] mt-2">Total Judul</p>
                </div>
                <div class="h-10 sm:h-16 w-1 sm:w-2 bg-slate-900 rotate-[15deg]"></div>
                <div class="text-center group">
                    <p class="text-3xl sm:text-5xl font-bold text-slate-900 group-hover:text-purple-600 transition-colors italic">{{ $books->sum(fn($b) => $b->items->count()) }}</p>
                    <p class="text-[8px] sm:text-[10px] font-bold text-slate-400 tracking-[0.1em] sm:tracking-[0.2em] mt-2">Unit Tersedia</p>
                </div>
            </div>

            <!-- Search Area -->
            <div class="max-w-xl mx-auto relative group px-2">
                <div class="relative flex bg-white border-[4px] sm:border-[6px] border-slate-900 rounded-xl sm:rounded-2xl overflow-hidden shadow-[8px_8px_0px_#9333ea] sm:shadow-[12px_12px_0px_#9333ea] transition-all duration-300">
                    <div class="bg-slate-900 text-white px-4 sm:px-8 flex items-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="Cari..." 
                           class="flex-1 py-4 sm:py-7 px-4 sm:px-8 text-lg sm:text-xl font-bold text-slate-900 placeholder:text-slate-400 outline-none italic">
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 pb-20 sm:pb-24">
            <div class="flex items-center gap-4 sm:gap-6 mb-10 sm:16">
                <div class="h-4 w-4 sm:h-6 sm:w-6 bg-slate-900 comic-plus border-2 border-white shadow-sm rotate-12"></div>
                <h2 class="text-[10px] sm:text-sm font-bold text-slate-900 tracking-[0.1em] sm:tracking-[0.2em] italic bg-white px-3 sm:px-4 border-2 border-slate-900 shadow-[3px_3px_0px_#1e293b] sm:shadow-[4px_4px_0px_#1e293b]">Manifest Koleksi</h2>
                <div class="h-[3px] sm:h-[4px] flex-1 bg-slate-900"></div>
                <div class="h-4 w-4 sm:h-6 sm:w-6 bg-purple-600 comic-plus border-2 border-white shadow-sm -rotate-12"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12" id="booksGrid">
                @forelse($books as $book)
                <div class="book-card group" data-title="{{ strtolower($book->title) }}" data-author="{{ strtolower($book->author ?? '') }}">
                    <div class="relative bg-white panel-border rounded-2xl overflow-hidden transition-all duration-500 hover:translate-y-[-10px] hover:shadow-[24px_24px_0px_#9333ea] flex flex-col sm:flex-row h-full group/card">
                        
                        <!-- Tape Effects -->
                        <div class="tape-effect -top-2 -left-6 opacity-60 group-hover/card:opacity-100 transition-opacity"></div>
                        <div class="tape-effect -bottom-2 -right-6 rotate-[135deg] opacity-40 group-hover/card:opacity-80 transition-opacity"></div>

                        <!-- Left Section: Artwork -->
                        <div class="w-full sm:w-2/5 relative overflow-hidden bg-slate-100 border-b-4 sm:border-b-0 sm:border-r-4 border-slate-900 aspect-[3/4] sm:aspect-auto">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" 
                                     class="w-full h-full object-cover transition-all duration-700 group-hover/card:scale-110 grayscale-[10%] group-hover/card:grayscale-0">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-200 p-8 benday-dots">
                                    <svg class="w-16 h-16 mb-4 opacity-40 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Security Stamp -->
                            <div class="security-stamp top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -rotate-[25deg] text-purple-600 border-purple-600 scale-150">Asli</div>

                            <!-- Floating Year Chip -->
                            <div class="absolute top-4 left-4 sticker-effect z-10">
                                <span class="bg-white border-[3px] border-slate-900 text-slate-900 text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase tracking-widest shadow-[4px_4px_0px_#1e293b] group-hover/card:bg-purple-600 group-hover/card:text-white transition-colors">
                                    {{ $book->year ?? 'Null' }}
                                </span>
                            </div>

                            <!-- Decorative Overlay -->
                            <div class="absolute inset-0 bg-purple-600/10 opacity-0 group-hover/card:opacity-100 transition-all duration-500 benday-dots" style="background-size: 8px 8px; opacity: 0.1;"></div>
                        </div>

                        <!-- Right Section: Info (Press Pass Style) -->
                        <div class="flex-1 p-6 sm:p-8 flex flex-col bg-slate-50 relative overflow-hidden perforated-divider">
                            <!-- Background Pattern for Card Info -->
                            <div class="absolute inset-0 opacity-[0.05] pointer-events-none screentone"></div>
                            
                            <!-- Internal Barcode Visual -->
                            <div class="absolute top-6 right-6 opacity-20 group-hover/card:opacity-40 transition-opacity hidden sm:block">                            </div>

                            <div class="relative z-10 flex flex-col h-full">
                                <div class="mb-4">
                                    <div class="bg-slate-900 text-white text-[10px] font-bold px-3 py-1.5 rounded border border-white shadow-[4px_4px_0px_#9333ea] uppercase tracking-tight italic w-fit mb-4">
                                        <p>Koleksi</p>
                                    </div>

                                    <span class="text-[15px] font-bold text-slate-400 tracking-widest mb-2 block">Manifest Judul</span>
                                    <h3 class="{{ strlen($book->title) > 50 ? 'text-lg sm:text-2xl' : (strlen($book->title) > 25 ? 'text-xl sm:text-3xl' : 'text-2xl sm:text-4xl') }} font-bold text-slate-900 leading-[0.9] group-hover/card:text-purple-600 transition-colors line-clamp-2 tracking-tight mb-4">
                                        {{ $book->title }}
                                    </h3>
                                </div>
                                
                                <div class="mt-auto pt-6 border-t-4 border-slate-900 border-dotted space-y-4">
                                    <!-- Author Block -->
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <span class="text-[15px] font-bold text-slate-400 tracking-widest uppercase">Data Penulis</span>
                                            <div class="h-[1px] flex-1 bg-slate-100 tech-grid opacity-50"></div>
                                        </div>
                                        <p class="text-xl font-bold text-slate-900 tracking-tight leading-[1.1] break-words">
                                            {{ $book->author ?? 'Tidak Diketahui' }}
                                        </p>
                                    </div>

                                    <!-- Status Block -->
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex flex-col gap-1.5">
                                            <!-- Mini Decorative Barcode -->
                                            <div class="flex items-end gap-[2px] opacity-30">
                                                <div class="w-1 h-3 bg-slate-500"></div>
                                                <div class="w-[2px] h-4 bg-slate-500"></div>
                                                <div class="w-1.5 h-2 bg-slate-500"></div>
                                                <div class="w-1 h-5 bg-slate-500"></div>
                                                <div class="w-[2px] h-3 bg-slate-500"></div>
                                                <div class="w-2 h-4 bg-slate-500"></div>
                                                <div class="w-1 h-2 bg-slate-500"></div>
                                                <div class="w-[2px] h-5 bg-slate-500"></div>
                                                <div class="w-1 h-3 bg-slate-500"></div>
                                                <div class="w-1.5 h-4 bg-slate-500"></div>
                                                <div class="w-1 h-5 bg-slate-500"></div>
                                                <div class="w-[2px] h-3 bg-slate-500"></div>
                                            </div>
                                            <div class="text-[7px] font-bold text-slate-400 tracking-[0.2em] uppercase italic leading-none">
                                                SMK YAJ DEPOK
                                            </div>
                                        </div>
                                        
                                        <!-- Status Sticker -->
                                        <div class="inline-flex shrink-0 border-[3px] border-slate-900 rounded-xl overflow-hidden w-fit shadow-[6px_6px_0px_#9333ea] sticker-effect transition-all group-hover/card:scale-110 active:scale-95">
                                            <span class="bg-slate-900 text-white px-3 py-1.5 text-[10px] font-bold tracking-tight border-r-[3px] border-slate-900 italic">Stok</span>
                                            <span class="px-4 py-1.5 text-xs font-bold {{ $book->available_stock > 0 ? 'bg-white text-purple-600' : 'bg-slate-100 text-slate-400' }} italic">
                                                {{ $book->available_stock > 0 ? $book->available_stock : 'HABIS' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-40 text-center relative overflow-hidden">
                    <div class="bg-white panel-border rounded-[3rem] p-16 relative z-10">
                        <div class="w-24 h-24 bg-slate-900 rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-[12px_12px_0px_#9333ea] -rotate-6 animate-bounce">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-5xl font-bold text-slate-900 mb-4">Manifest Kosong</h3>
                        <p class="text-slate-400 font-bold tracking-[0.1em] italic">Repositori digital saat ini tidak berisi entri yang sesuai dengan kueri Anda.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-16 px-6 relative">
            <div class="max-w-7xl mx-auto border-t-2 border-slate-900 pt-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 rounded flex items-center justify-center p-2">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="max-w-full max-h-full ">
                    </div>
                    <div class="text-left">
                        <p class="text-[20px] font-bold text-slate-900 tracking-[0.2em] uppercase leading-none">PERPUSTAKAAN</p>
                        <p class="text-[15px] font-bold text-purple-600 uppercase tracking-widest mt-1">SMK YAJ DEPOK</p>
                    </div>
                </div>
                
                <p class="text-[9px] font-bold text-slate-400 tracking-[0.2em] italic bg-white px-4 py-2 border border-slate-200 rounded-full">
                    © Hak Cipta • {{ date('Y') }} • Kampunk Dev Team
                </p>
            </div>
        </footer>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const booksGrid = document.getElementById('booksGrid');
        const bookCards = booksGrid.querySelectorAll('.book-card');

        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            
            bookCards.forEach(card => {
                const title = card.dataset.title || '';
                const author = card.dataset.author || '';
                
                if (title.includes(query) || author.includes(query)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
