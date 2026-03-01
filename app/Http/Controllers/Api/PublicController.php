<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PublicController extends Controller
{
    /**
     * Get public catalog of books.
     * Cached for 5 minutes for performance.
     */
    public function books(Request $request)
    {
        $cacheKey = 'public_books_' . md5($request->fullUrl());
        
        return Cache::remember($cacheKey, 300, function () use ($request) {
            $query = Book::with(['items' => function ($q) {
                $q->select('id', 'book_id', 'status');
            }]);

            // Search by title or author
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%");
                });
            }

            // Filter by year
            if ($request->has('year')) {
                $query->where('year', $request->year);
            }

            $books = $query->orderBy('title')->get();

            return response()->json([
                'success' => true,
                'data' => $books->map(function ($book) {
                    return [
                        'id' => $book->id,
                        'title' => $book->title,
                        'author' => $book->author,
                        'publisher' => $book->publisher,
                        'year' => $book->year,
                        'isbn' => $book->isbn,
                        'cover_image' => $book->cover_image ? asset('storage/' . $book->cover_image) : null,
                        'total_stock' => $book->items->count(),
                        'available_stock' => $book->items->where('status', 'available')->count(),
                    ];
                }),
                'total' => $books->count(),
            ]);
        });
    }
}
