<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --bg-dark: #0f1729;
            --bg-card: rgba(30, 41, 59, 0.7);
            --text: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-dark);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Animated Background */
        .bg-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(ellipse at 20% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(59, 130, 246, 0.05) 0%, transparent 70%);
            z-index: -1;
        }

        /* Hero Section */
        .hero {
            padding: 80px 20px 60px;
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            color: #a5b4fc;
            margin-bottom: 24px;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #fff 0%, #a5b4fc 50%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.2rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto 32px;
            line-height: 1.7;
        }

        /* Stats */
        .stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .stat {
            text-align: center;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        /* Search */
        .search-container {
            max-width: 500px;
            margin: 0 auto 60px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 16px 24px 16px 50px;
            background: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            font-size: 1rem;
            color: var(--text);
            outline: none;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }

        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        /* Books Grid */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 80px;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }

        .book-card {
            background: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .book-card:hover {
            transform: translateY(-8px);
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .book-cover {
            height: 200px;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-cover-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #475569;
        }

        .book-cover-placeholder svg {
            width: 48px;
            height: 48px;
            margin-bottom: 8px;
        }

        .book-info {
            padding: 20px;
        }

        .book-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 8px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .book-author {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        .book-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .book-year {
            font-size: 0.85rem;
            color: #64748b;
        }

        .stock-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .stock-available {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
        }

        .stock-empty {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--text-muted);
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 40px 20px;
            color: #475569;
            font-size: 0.9rem;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <div class="bg-gradient"></div>

    <section class="hero">
        <div class="hero-badge">
            <span>📚</span>
            <span>Perpustakaan Digital</span>
        </div>
        <h1>Temukan Buku Favorit Kamu</h1>
        <p>Jelajahi ribuan koleksi buku di perpustakaan kami. Baca, pinjam, dan kembangkan pengetahuanmu bersama kami.</p>
        
        <div class="stats">
            <div class="stat">
                <div class="stat-value">{{ $books->count() }}</div>
                <div class="stat-label">Judul Buku</div>
            </div>
            <div class="stat">
                <div class="stat-value">{{ $books->sum(fn($b) => $b->items->count()) }}</div>
                <div class="stat-label">Total Eksemplar</div>
            </div>
            <div class="stat">
                <div class="stat-value">{{ $books->sum(fn($b) => $b->available_stock) }}</div>
                <div class="stat-label">Tersedia</div>
            </div>
        </div>

        <div class="search-container">
            <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <input type="text" class="search-input" placeholder="Cari judul atau penulis..." id="searchInput">
        </div>
    </section>

    <div class="container">
        <h2 class="section-title">
            <span>📖</span>
            Koleksi Buku
        </h2>

        <div class="books-grid" id="booksGrid">
            @forelse($books as $book)
            <div class="book-card" data-title="{{ strtolower($book->title) }}" data-author="{{ strtolower($book->author ?? '') }}">
                <div class="book-cover">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                    @else
                        <div class="book-cover-placeholder">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span style="font-size: 0.8rem">No Cover</span>
                        </div>
                    @endif
                </div>
                <div class="book-info">
                    <h3 class="book-title">{{ $book->title }}</h3>
                    <p class="book-author">{{ $book->author ?? 'Penulis tidak diketahui' }}</p>
                    <div class="book-meta">
                        <span class="book-year">{{ $book->year ?? '-' }}</span>
                        <span class="stock-badge {{ $book->available_stock > 0 ? 'stock-available' : 'stock-empty' }}">
                            {{ $book->available_stock }} tersedia
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state" style="grid-column: 1 / -1;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3>Belum ada koleksi buku</h3>
                <p>Koleksi buku akan segera hadir!</p>
            </div>
            @endforelse
        </div>
    </div>

    <footer class="footer">
        <p>© {{ date('Y') }} Perpustakaan Digital. Semua hak cipta dilindungi.</p>
    </footer>

    <script>
        // Simple search functionality
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
