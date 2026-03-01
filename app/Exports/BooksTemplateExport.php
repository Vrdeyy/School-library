<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksTemplateExport implements WithHeadings
{
    public function headings(): array
    {
        return [
            'title',
            'author',
            'publisher',
            'year',
            'isbn',
            'stock',
        ];
    }
}
