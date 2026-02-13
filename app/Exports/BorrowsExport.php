<?php

namespace App\Exports;

use App\Models\Borrow;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BorrowsExport implements FromQuery, WithHeadings, WithMapping
{
    protected int $month;
    protected int $year;

    public function __construct(int $month, int $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function query()
    {
        return Borrow::query()
            ->with(['user', 'bookItem.book'])
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year);
    }

    public function headings(): array
    {
        return [
            'Nama Peminjam',
            'No Telp',
            'Judul Buku',
            'Kode Buku',
            'Tanggal Pinjam',
            'Tanggal Kembali',
        ];
    }

    public function map($borrow): array
    {
        return [
            $borrow->user->name ?? '-',
            $borrow->user->phone ?? '-',
            $borrow->bookItem->book->title ?? '-',
            $borrow->bookItem->code ?? '-',
            $borrow->borrow_date?->format('Y-m-d H:i'),
            $borrow->return_date?->format('Y-m-d H:i'),
        ];
    }
}