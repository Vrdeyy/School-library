<?php

namespace App\Exports;

use App\Models\Borrow;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class BorrowsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected int $month;
    protected int $year;

    public function __construct(int $month, int $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function title(): string
    {
        return 'Laporan Peminjaman ' . Carbon::create()->month($this->month)->format('F') . ' ' . $this->year;
    }

    public function query()
    {
        return Borrow::query()
            ->with(['user', 'bookItem.book'])
            ->whereMonth('borrow_date', $this->month)
            ->whereYear('borrow_date', $this->year)
            ->orderBy('borrow_date', 'asc');
    }

    public function headings(): array
    {
        return [
            'NAMA PEMINJAM',
            'NO. TELEPON',
            'JUDUL BUKU',
            'KODE BUKU',
            'TANGGAL PINJAM',
            'TANGGAL KEMBALI',
            'STATUS',
        ];
    }

    public function map($borrow): array
    {
        return [
            strtoupper($borrow->user->name ?? '-'),
            $borrow->user->phone ?? '-',
            strtoupper($borrow->bookItem->book->title ?? '-'),
            $borrow->bookItem->code ?? '-',
            $borrow->borrow_date ? $borrow->borrow_date->format('d M Y') : '-',
            $borrow->return_date ? $borrow->return_date->format('d M Y') : '-',
            strtoupper($borrow->status),
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