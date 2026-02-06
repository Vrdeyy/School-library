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
            'borrow_id',
            'user_id',
            'user_name',
            'user_phone',
            'book_item_id',
            'book_title',
            'borrow_date',
            'due_date',
            'return_date',
            'status',
        ];
    }

    public function map($borrow): array
    {
        return [
            $borrow->id,
            $borrow->user_id,
            $borrow->user->name ?? '-',
            $borrow->user->phone ?? '-',
            $borrow->book_item_id,
            $borrow->bookItem->book->title ?? '-',
            $borrow->borrow_date?->format('Y-m-d H:i'),
            $borrow->due_date?->format('Y-m-d H:i'),
            $borrow->return_date?->format('Y-m-d H:i'),
            $borrow->status,
        ];
    }
}