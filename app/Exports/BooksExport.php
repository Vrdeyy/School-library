<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BooksExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function title(): string
    {
        return 'Koleksi Buku Perpustakaan';
    }

    public function query()
    {
        return Book::with('items')->orderBy('title');
    }

    public function headings(): array
    {
        return [
            'NO',
            'JUDUL BUKU',
            'PENULIS',
            'PENERBIT',
            'TAHUN',
            'ISBN',
            'TOTAL EKSEMPLAR',
            'TERSEDIA',
            'DAFTAR KODE BUKU',
        ];
    }

    public function map($book): array
    {
        static $row = 0;
        $row++;
        
        $itemCodes = $book->items->pluck('code')->implode(', ');

        return [
            $row,
            strtoupper($book->title),
            $book->author ?? '-',
            $book->publisher ?? '-',
            $book->year ?? '-',
            $book->isbn ?? '-',
            $book->items->count() . ' UNIT',
            $book->items->where('status', 'available')->count() . ' UNIT',
            $itemCodes ?: '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'], // Indigo
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}
