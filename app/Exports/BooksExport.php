<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BooksExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Book::with('items')->orderBy('title');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Author',
            'Publisher',
            'Year',
            'ISBN',
            'Total Stock',
            'Available Stock',
            'Items Codes',
        ];
    }

    public function map($book): array
    {
        $itemCodes = $book->items->pluck('code')->implode(', ');

        return [
            $book->id,
            $book->title,
            $book->author,
            $book->publisher,
            $book->year,
            $book->isbn,
            $book->items->count(),
            $book->items->where('status', 'available')->count(),
            $itemCodes,
        ];
    }
}
