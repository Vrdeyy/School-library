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
            background-image: repeating-linear-gradient(135deg, #475569 0, #475569 0.5px, transparent 0.5px, transparent 15px);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen overflow-x-hidden selection:bg-blue-600 selection:text-white">
    
    <!-- Background Elements -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-texture opacity-[0.08]"></div>
        <div class="absolute inset-0 opacity-[0.05]" 
             style="background-image: linear-gradient(to right, #1e293b 1px, transparent 1px), linear-gradient(to bottom, #1e293b 1px, transparent 1px); background-size: 40px 40px;"></div>
        <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-blue-100/60 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-indigo-100/60 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10">
        <!-- Header/Hero -->
        <header class="pt-20 pb-16 px-6 text-center max-w-5xl mx-auto">
            <div class="inline-flex items-center gap-3 px-6 py-2 rounded-full bg-white border border-slate-200 shadow-xl mb-10 animate-float">
                <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                <span class="text-[10px] font-black tracking-[0.3em] text-slate-400 uppercase">OFFICIAL_DIGITAL_CATALOG</span>
            </div>

            <h1 class="text-6xl sm:text-8xl font-black text-slate-900 tracking-tighter uppercase italic leading-none mb-6">
                EXPLORE<br>
                <span class="text-blue-600 shadow-blue-600/10">KNOWLEDGE.</span>
            </h1>
            
            <p class="text-slate-500 text-lg font-bold tracking-widest uppercase italic max-w-2xl mx-auto mb-12">
                Browse through our extensive cryptographic library collection and discover your next intellectual journey.
            </p>

            <div class="flex justify-center gap-12 flex-wrap mb-16">
                <div class="text-center group">
                    <p class="text-4xl font-black text-slate-900 group-hover:text-blue-600 transition-colors">{{ $books->count() }}</p>
                    <p class="text-[9px] font-black text-slate-400 tracking-[0.4em] uppercase mt-1">TOTAL_TITLES</p>
                </div>
                <div class="h-12 w-px bg-slate-200"></div>
                <div class="text-center group">
                    <p class="text-4xl font-black text-slate-900 group-hover:text-blue-600 transition-colors">{{ $books->sum(fn($b) => $b->items->count()) }}</p>
                    <p class="text-[9px] font-black text-slate-400 tracking-[0.4em] uppercase mt-1">AVAILABLE_UNITS</p>
                </div>
            </div>

            <!-- Search Area -->
            <div class="max-w-2xl mx-auto relative group">
                <div class="absolute -inset-1 bg-blue-100 rounded-[2rem] opacity-40 group-focus-within:opacity-60 blur-xl transition-all"></div>
                <div class="relative flex bg-white border-2 border-slate-900 rounded-[2rem] overflow-hidden shadow-[10px_10px_0px_#2563eb]">
                    <div class="bg-slate-100 text-slate-400 px-8 flex items-center border-r border-slate-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="SEARCH_BY_TITLE_OR_AUTHOR..." 
                           class="flex-1 py-6 px-8 text-xl font-black text-slate-900 placeholder:text-slate-200 outline-none uppercase italic">
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-6 pb-24">
            <div class="flex items-center gap-4 mb-10">
                <div class="h-px flex-1 bg-slate-200"></div>
                <h2 class="text-xs font-black text-slate-400 tracking-[0.5em] uppercase italic">COLLECTION_MANIFEST</h2>
                <div class="h-px flex-1 bg-slate-200"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="booksGrid">
                @forelse($books as $book)
                <div class="book-card group" data-title="{{ strtolower($book->title) }}" data-author="{{ strtolower($book->author ?? '') }}">
                    <div class="relative bg-white border-2 border-slate-200 rounded-[3rem] overflow-hidden transition-all duration-500 hover:border-blue-600 hover:translate-y-[-8px] hover:shadow-[12px_12px_0px_#2563eb]">
                        <!-- Cover Area -->
                        <div class="aspect-[3/4] relative overflow-hidden bg-slate-100">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-200 p-8">
                                    <svg class="w-20 h-20 mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400">NO_COVER_IMG</span>
                                </div>
                            @endif
                            
                            <!-- Year Badge -->
                            <div class="absolute top-6 right-6">
                                <span class="bg-white/80 backdrop-blur-md border border-slate-100 text-slate-900 text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest italic shadow-sm">
                                    {{ $book->year ?? 'N/A' }}
                                </span>
                            </div>
                        </div>

                        <!-- Content Area -->
                        <div class="p-8 space-y-6">
                            <div class="space-y-2">
                                <p class="text-[9px] font-black text-blue-600 uppercase tracking-[0.4em] italic">AUTHORED_BY:</p>
                                <h3 class="text-xl font-black text-slate-900 uppercase italic leading-tight line-clamp-2 min-h-[3.5rem] group-hover:text-blue-600 transition-colors">
                                    {{ $book->title }}
                                </h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest line-clamp-1 italic">
                                    {{ $book->author ?? 'UNKNOWN_CREATOR' }}
                                </p>
                            </div>

                            <div class="pt-6 border-t border-slate-100 flex justify-between items-center">
                                <div class="space-y-1">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-[0.3em]">STATE:</p>
                                    <div class="flex items-center gap-2">
                                        <div class="h-2 w-2 rounded-full {{ $book->available_stock > 0 ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                        <span class="text-[10px] font-black uppercase tracking-widest {{ $book->available_stock > 0 ? 'text-green-500' : 'text-red-500' }}">
                                            {{ $book->available_stock > 0 ? 'INSTOCK' : 'UNAVAILABLE' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                                    <span class="text-slate-900 font-black text-sm">{{ $book->available_stock }}</span>
                                    <span class="text-slate-400 text-[8px] font-black uppercase tracking-tighter ml-1">QTY</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-32 text-center">
                    <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center mx-auto mb-8 border-2 border-dashed border-slate-200">
                        <svg class="w-16 h-16 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 uppercase italic mb-4">MANIFEST_EMPTY</h3>
                    <p class="text-slate-400 font-bold uppercase tracking-widest italic">The cryptographic repository currently contains no indexed entries.</p>
                </div>
                @endforelse
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-12 border-t border-slate-100 text-center">
            <p class="text-[9px] font-black text-slate-300 tracking-[0.5em] uppercase italic">
                © {{ date('Y') }} PERPUSTAKAAN DIGITAL SMK YAJ DEPOK | CRYPTOGRAPHIC_VAULT_SYSTEM
            </p>
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
