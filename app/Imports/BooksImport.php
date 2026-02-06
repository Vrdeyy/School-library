<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\BookItem;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BooksImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $book = Book::create([
            'title'       => (string)$row['title'],
            'author'      => isset($row['author']) ? (string)$row['author'] : null,
            'publisher'   => isset($row['publisher']) ? (string)$row['publisher'] : null,
            'year'        => isset($row['year']) ? $row['year'] : null,
            'isbn'        => isset($row['isbn']) ? (string)$row['isbn'] : null,
            'description' => isset($row['description']) ? (string)$row['description'] : null,
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

        return $book;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'author' => 'nullable|max:255',
            'stock' => 'nullable|numeric|min:1|max:100',
            'isbn' => 'nullable|max:255',
        ];
    }
}
