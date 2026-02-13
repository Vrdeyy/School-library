<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Perpustakaan | SMK YAJ DEPOK</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Outfit', sans-serif; }
        
        .bg-texture {
            background-image: repeating-linear-gradient(135deg, #475569 0, #475569 0.5px, transparent 0.5px, transparent 8px);
        }

        .tech-grid {
            mask-image: linear-gradient(to bottom, white, transparent);
        }

        @keyframes float-gentle {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -20px) rotate(2deg); }
            66% { transform: translate(-20px, 10px) rotate(-2deg); }
        }
        .animate-float-gentle { animation: float-gentle 12s ease-in-out infinite; }
        
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

        /* Comic & Pop Art Textures */
        .comic-halftone {
            background-image: radial-gradient(#1e293b 15%, transparent 16%);
            background-size: 12px 12px;
            opacity: 0.1;
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
            opacity: 0.95;
        }

        .screentone {
            background-image: radial-gradient(circle, #1e293b 1px, transparent 0);
            background-size: 8px 8px;
            opacity: 0.05;
        }

        /* Depth & Textures */
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

        .comic-burst {
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
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
            -webkit-text-stroke: 2px #1e293b;
            color: white;
            filter: drop-shadow(4px 4px 0px #2563eb);
            letter-spacing: -0.05em;
        }

        .chromatic-offset {
            text-shadow: 
                -2px -2px 0 rgba(255,0,0,0.5),
                2px 2px 0 rgba(0,255,255,0.5);
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
            border: 4px solid #1e293b;
            box-shadow: 8px 8px 0px #1e293b;
        }

        .benday-dots {
            background-image: radial-gradient(#1e293b 20%, transparent 20%);
            background-size: 4px 4px;
        }
    </style>
</head>
<body class="text-slate-900 min-h-screen overflow-x-hidden selection:bg-blue-600 selection:text-white paper-texture">
    <div class="grain"></div>
    <!-- Background Elements (Comic/Pop-Art Layered Version) -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden bg-slate-50">
        
        <!-- Layer 0: Comic Halftone Global Texture -->
        <div class="absolute inset-0 z-0 comic-halftone"></div>
        <div class="absolute inset-0 z-0 screentone"></div>

        <!-- Layer 1: Ink Splashes (Vibrant Blobs) -->
        <div class="absolute inset-0 z-[1] mix-blend-multiply">
            <div class="absolute top-[-5%] left-[-10%] w-[600px] h-[600px] bg-blue-500 mesh-blob animate-drift"></div>
            <div class="absolute bottom-[-5%] right-[-10%] w-[700px] h-[700px] bg-indigo-500 mesh-blob animate-drift" style="animation-delay: -15s"></div>
            <div class="absolute top-[40%] right-[20%] w-[500px] h-[500px] bg-purple-500 mesh-blob animate-drift" style="animation-delay: -25s"></div>
        </div>

        <!-- Layer 2: Comic Hatching Corners & Speed Lines -->
        <div class="absolute inset-0 z-[2] speed-lines opacity-100"></div>
        <div class="absolute top-0 right-0 w-[40%] h-[40%] z-[2] comic-hatch"></div>
        <div class="absolute bottom-0 left-0 w-[50%] h-[30%] z-[2] comic-hatch opacity-60"></div>

        <!-- Layer 3: Pop-Art Bursts (Stars) -->
        <div class="absolute top-[10%] right-[15%] w-64 h-64 bg-indigo-100/40 comic-burst z-[3] rotate-12 animate-pulse"></div>
        <div class="absolute bottom-[20%] left-[5%] w-48 h-48 bg-purple-100/40 comic-burst z-[3] -rotate-12 animate-bounce" style="animation-delay: 2s"></div>

        <!-- Layer 4: Tech Grid & Lines (Offset Comic Style) -->
        <svg class="absolute inset-0 opacity-[0.25] text-blue-900 z-[4] tech-grid" fill="none" stroke="currentColor" viewBox="0 0 100 100">
            <defs>
                <pattern id="comicGrid" width="20" height="20" patternUnits="userSpaceOnUse">
                    <path d="M 20 0 L 0 0 0 20" fill="none" stroke="currentColor" stroke-width="0.2"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#comicGrid)" />
        </svg>

        <!-- Layer 5: Silicon/Circuit Lines (Bold Comic Lines) -->
        <svg class="absolute inset-0 opacity-[0.35] text-blue-700 z-[5]" viewBox="0 0 1000 1000">
            <!-- Bold paths with offset look -->
            <path d="M0 150 H300 L350 200 H700 L750 250 H1000" fill="none" stroke="currentColor" stroke-width="4" stroke-dasharray="10 10" />
            <path d="M1000 750 H750 L700 800 H350 L300 850 H0" fill="none" stroke="currentColor" stroke-width="3" />
            
            <circle cx="350" cy="200" r="8" fill="white" stroke="currentColor" stroke-width="3" />
            <circle cx="700" cy="800" r="8" fill="white" stroke="currentColor" stroke-width="3" />
        </svg>

        <!-- Layer 6: Silhouetted Library Icons (Sticker Style) -->
        <div class="absolute inset-0 z-[6] text-blue-900/20">
            <svg class="absolute top-[20%] left-[8%] w-40 h-40 rotate-12 animate-float-gentle sticker-effect" viewBox="0 0 24 24" fill="currentColor">
                <path d="M22 10v6M2 10l10-5 10 5-10 5zM6 12v5c0 2 2 3 6 3s6-1 6-3v-5" />
            </svg>
            <svg class="absolute bottom-[25%] right-[10%] w-56 h-56 -rotate-12 animate-float-gentle sticker-effect" style="animation-delay: -5s" viewBox="0 0 24 24" fill="currentColor">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2zM22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
            </svg>
        </div>

        <!-- Layer 7: Onomatopoeia & Ink Splats -->
        <div class="absolute inset-0 z-[7] pointer-events-none">
            <div class="absolute top-[12%] left-[10%] sm:top-[15%] sm:left-[25%] animate-bounce" style="animation-duration: 3s">
                <span class="onomatopoeia text-2xl sm:text-4xl lg:text-5xl -rotate-12">WOW!</span>
            </div>
            <div class="absolute bottom-[35%] right-[5%] sm:bottom-[40%] sm:right-[15%] animate-pulse">
                <span class="onomatopoeia text-2xl sm:text-3xl lg:text-4xl rotate-12" style="filter: drop-shadow(4px 4px 0px #4f46e5);">BOOM!</span>
            </div>
            <div class="absolute top-[55%] left-[2%] sm:top-[60%] sm:left-[5%] animate-float-gentle opacity-50 sm:opacity-100">
                <span class="onomatopoeia text-xl sm:text-2xl lg:text-3xl -rotate-6" style="filter: drop-shadow(4px 4px 0px #7c3aed);">READ!</span>
            </div>

            <!-- Ink Splats -->
            <div class="absolute top-[5%] right-[5%] w-20 h-20 sm:w-32 sm:h-32 bg-blue-600/10 ink-splat rotate-45"></div>
            <div class="absolute bottom-[10%] left-[10%] sm:left-[20%] w-32 h-32 sm:w-48 sm:h-48 bg-indigo-600/10 ink-splat -rotate-12"></div>
        </div>

        <!-- Layer 8: Zig-Zag Accents -->
        <div class="absolute top-1/4 left-0 w-full h-10 zig-zag opacity-5 z-[8]"></div>
        <div class="absolute bottom-1/4 left-0 w-full h-10 zig-zag opacity-5 z-[8]"></div>

        <div class="scanline z-[10]"></div>
    </div>

    <div class="relative z-10">
        <!-- Header/Hero -->
        <header class="pt-16 sm:pt-24 pb-12 sm:pb-16 px-4 sm:px-6 text-center max-w-5xl mx-auto">
            <div class="inline-flex items-center gap-3 px-4 sm:px-6 py-2 rounded-full bg-white border-2 border-slate-900 shadow-[4px_4px_0px_#2563eb] mb-8 sm:12 relative">
                <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                <span class="text-[8px] sm:text-[10px] font-black tracking-[0.2em] sm:tracking-[0.4em] text-slate-900 uppercase">OFFICIAL_DIGITAL_CATALOG</span>
                <!-- Mini burst sticker -->
                <div class="absolute -top-3 -right-3 sm:-top-4 sm:-right-4 w-8 h-8 sm:w-10 sm:h-10 bg-blue-600 comic-burst flex items-center justify-center rotate-12 shadow-lg">
                    <span class="text-[7px] sm:text-[8px] font-black text-white italic">NEW</span>
                </div>
            </div>

            <h1 class="text-4xl sm:text-6xl lg:text-8xl font-black text-slate-900 tracking-tighter uppercase italic leading-none mb-6 sm:8 relative chromatic-offset">
                EXPLORE<br>
                <span class="text-blue-600" style="text-shadow: 2px 2px 0 white, 4px 4px 0px #1e293b;">KNOWLEDGE.</span>
                <!-- Floating accent dots - hidden on very small screens -->
                <div class="absolute -top-4 sm:-top-6 left-0 flex gap-1 sm:gap-2">
                    <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-600 rounded-full border-2 border-slate-900 shadow-[1px_1px_0px_white]"></div>
                    <div class="w-2 h-2 sm:w-3 sm:h-3 bg-indigo-600 rounded-full border-2 border-slate-900 shadow-[1px_1px_0px_white]"></div>
                </div>
            </h1>
            
            <p class="text-slate-500 text-sm sm:text-lg font-bold tracking-[0.1em] sm:tracking-[0.2em] uppercase italic max-w-2xl mx-auto mb-10 sm:16 -mt-2">
                Browse through our premium collection of academic resources.
            </p>

            <div class="flex justify-center gap-6 sm:gap-12 flex-wrap mb-12 sm:20">
                <div class="text-center group">
                    <p class="text-3xl sm:text-5xl font-black text-slate-900 group-hover:text-blue-600 transition-colors italic">0{{ $books->count() }}</p>
                    <p class="text-[8px] sm:text-[10px] font-black text-slate-400 tracking-[0.3em] sm:tracking-[0.5em] uppercase mt-2">TOTAL_TITLES</p>
                </div>
                <div class="h-10 sm:h-16 w-1 sm:w-2 bg-slate-900 rotate-[15deg]"></div>
                <div class="text-center group">
                    <p class="text-3xl sm:text-5xl font-black text-slate-900 group-hover:text-blue-600 transition-colors italic">{{ $books->sum(fn($b) => $b->items->count()) }}</p>
                    <p class="text-[8px] sm:text-[10px] font-black text-slate-400 tracking-[0.3em] sm:tracking-[0.5em] uppercase mt-2">AVAILABLE_UNITS</p>
                </div>
            </div>

            <!-- Search Area -->
            <div class="max-w-xl mx-auto relative group px-2">
                <div class="absolute -top-7 -left-2 z-20">
                    <div class="comic-burst bg-blue-600 text-white px-3 py-2 sm:px-4 sm:py-3 rotate-[-15deg] shadow-[3px_3px_0px_#1e293b] animate-pulse border-2 border-slate-900">
                        <span class="text-[10px] sm:text-xs font-black italic uppercase">FIND IT!</span>
                    </div>
                </div>
                <div class="relative flex bg-white border-[4px] sm:border-[6px] border-slate-900 rounded-xl sm:rounded-2xl overflow-hidden shadow-[8px_8px_0px_#2563eb] sm:shadow-[12px_12px_0px_#2563eb] transition-all duration-300">
                    <div class="bg-slate-900 text-white px-4 sm:px-8 flex items-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="SEARCH..." 
                           class="flex-1 py-4 sm:py-7 px-4 sm:px-8 text-lg sm:text-xl font-black text-slate-900 placeholder:text-slate-400 outline-none uppercase italic">
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 pb-20 sm:pb-24">
            <div class="flex items-center gap-4 sm:gap-6 mb-10 sm:16">
                <div class="h-3 w-3 sm:h-4 sm:w-4 bg-slate-900 rotate-45 border border-white shadow-sm"></div>
                <h2 class="text-[10px] sm:text-sm font-black text-slate-900 tracking-[0.3em] sm:tracking-[0.6em] uppercase italic bg-white px-3 sm:px-4 border-2 border-slate-900 shadow-[3px_3px_0px_#1e293b] sm:shadow-[4px_4px_0px_#1e293b]">COLLECTION_MANIFEST</h2>
                <div class="h-[3px] sm:h-[4px] flex-1 bg-slate-900"></div>
                <div class="h-3 w-3 sm:h-4 sm:w-4 bg-blue-600 rotate-45 border border-white shadow-sm"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-10 lg:gap-12" id="booksGrid">
                @forelse($books as $book)
                <div class="book-card group" data-title="{{ strtolower($book->title) }}" data-author="{{ strtolower($book->author ?? '') }}">
                    <div class="relative bg-white panel-border rounded-xl overflow-hidden transition-all duration-300 hover:translate-y-[-10px] hover:shadow-[16px_16px_0px_#2563eb] flex flex-col h-full group/card">
                        
                        <!-- Top Accent Bar -->
                        <div class="h-[4mm] bg-slate-900 w-full group-hover/card:bg-blue-600 transition-colors flex items-center px-4 gap-1">
                            <div class="w-1 h-1 bg-white rounded-full opacity-50"></div>
                            <div class="w-1 h-1 bg-white rounded-full opacity-50"></div>
                            <div class="w-1 h-1 bg-white rounded-full opacity-50"></div>
                        </div>

                        <!-- Header Artwork Area -->
                        <div class="aspect-[3/4] relative overflow-hidden bg-slate-50 border-b-4 border-slate-900">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" 
                                     class="w-full h-full object-cover transition-all duration-700 group-hover/card:scale-110 grayscale-[20%] group-hover/card:grayscale-0">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-200 p-8 benday-dots">
                                    <svg class="w-16 h-16 mb-4 opacity-40 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Floating Year Chip -->
                            <div class="absolute top-4 left-4">
                                <span class="bg-white border-[3px] border-slate-900 text-slate-900 text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest shadow-[4px_4px_0px_#1e293b] group-hover/card:bg-blue-600 group-hover/card:text-white transition-colors">
                                    {{ $book->year ?? 'N/A' }}
                                </span>
                            </div>

                            <!-- Decorative Overlay -->
                            <div class="absolute inset-0 bg-blue-600/10 opacity-0 group-hover/card:opacity-100 transition-all duration-500 benday-dots" style="background-size: 8px 8px; opacity: 0.1;"></div>
                        </div>

                        <!-- Info Area -->
                        <div class="p-6 flex-1 flex flex-col bg-slate-50 relative overflow-hidden border-t-2 border-slate-200">
                            <!-- Background Pattern for Card Info -->
                            <div class="absolute inset-0 opacity-[0.05] pointer-events-none screentone"></div>

                            <div class="relative z-10 flex flex-col h-full">
                                <!-- Book ID/Category Placeholder Style -->
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="bg-slate-900 text-white text-[8px] font-black px-2 py-1 rounded tracking-tighter uppercase">REG_ID_{{ $book->id }}</div>
                                    <div class="text-[8px] font-black text-blue-600 tracking-[0.2em] uppercase bg-blue-50 px-2 py-1 rounded border border-blue-100">{{ $book->category->name ?? 'GENERAL' }}</div>
                                </div>

                                <h3 class="text-xl font-black text-slate-900 uppercase italic leading-tight mb-4 group-hover/card:text-blue-600 transition-colors line-clamp-2">
                                    {{ $book->title }}
                                </h3>
                                
                                <div class="mt-auto pt-4 border-t-4 border-slate-900 border-dotted flex flex-col gap-4">
                                    <div class="flex flex-col">
                                        <label class="text-[9px] font-black text-slate-400 tracking-widest uppercase mb-1">AUTHOR_SPEC</label>
                                        <p class="text-[12px] font-black text-slate-900 uppercase tracking-tight truncate">{{ $book->author ?? 'UNKNOWN' }}</p>
                                    </div>

                                    <!-- Status Pill -->
                                    <div class="inline-flex border-[3px] border-slate-900 rounded-xl overflow-hidden w-fit shadow-[6px_6px_0px_#1e293b] group-hover/card:shadow-[6px_6px_0px_#2563eb] transition-all group-hover/card:translate-x-[-2px] group-hover/card:translate-y-[-2px]">
                                        <span class="bg-slate-900 text-white px-3 py-1.5 text-[9px] font-black uppercase tracking-tighter border-r-[3px] border-slate-900">STOCK</span>
                                        <span class="px-4 py-1.5 text-[11px] font-black {{ $book->available_stock > 0 ? 'bg-white text-blue-600' : 'bg-slate-100 text-slate-400' }}">
                                            {{ $book->available_stock > 0 ? 'AVAILABLE' : 'DEPLETED' }}
                                            [{{ $book->available_stock }}]
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-40 text-center relative overflow-hidden">
                    <div class="bg-white panel-border rounded-[3rem] p-16 relative z-10">
                        <div class="w-24 h-24 bg-slate-900 rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-[12px_12px_0px_#2563eb] -rotate-6 animate-bounce">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-5xl font-black text-slate-900 uppercase italic mb-4 chromatic-offset">MANIFEST_EMPTY</h3>
                        <p class="text-slate-400 font-bold uppercase tracking-[0.3em] italic">The digital repository currently contains no indexed entries matching your query.</p>
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
                        <p class="text-[20px] font-black text-slate-900 tracking-[0.2em] uppercase leading-none">PERPUSTAKAAN</p>
                        <p class="text-[15px] font-bold text-blue-600 uppercase tracking-widest mt-1">SMK YAJ DEPOK</p>
                    </div>
                </div>
                
                <p class="text-[9px] font-black text-slate-400 tracking-[0.5em] uppercase italic bg-white px-4 py-2 border border-slate-200 rounded-full">
                    ©   COPYRIGHT • {{ date('Y') }} • Kampunk Dev Team
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
