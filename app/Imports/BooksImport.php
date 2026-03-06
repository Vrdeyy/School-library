<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\BookItem;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class BooksImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip if mandatory fields are missing anyway (double check)
        if (empty($row['title'])) {
            return null;
        }

        $book = Book::create([
            'title'       => (string)$row['title'],
            'author'      => isset($row['author']) ? (string)$row['author'] : null,
            'publisher'   => isset($row['publisher']) ? (string)$row['publisher'] : null,
            'year'        => isset($row['year']) ? $row['year'] : null,
            'isbn'        => isset($row['isbn']) ? (string)$row['isbn'] : null,
        ]);

        $stock = intval($row['stock'] ?? 1);
        
        for ($i = 0; $i < $stock; $i++) {
            $code = $book->id . '-' . now()->format('ymdHi') . '-' . strtoupper(Str::random(5));
            
            BookItem::create([
                'book_id' => $book->id,
                'code' => $code,
                'status' => 'available',
            ]);
        }

        // Return null because we created the items manually and already saved the book
        return null;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'stock' => 'required|numeric|min:1|max:100', // Both title and stock are required
            'author' => 'nullable|max:255',
            'isbn' => 'nullable|max:255',
        ];
    }
}
