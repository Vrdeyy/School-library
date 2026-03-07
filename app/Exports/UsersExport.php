<?php

namespace App\Exports;

use App\Models\User;
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

class UsersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function title(): string
    {
        return 'Data Member Perpus';
    }

    public function query()
    {
        return User::query()->where('role', '!=', 'admin')->orderBy('name');
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA LENGKAP',
            'EMAIL',
            'NO. TELEPON',
            'PERAN/JABATAN',
            'NIS / ID PENGENAL',
            'STATUS AKUN',
            'BATAS PINJAM',
            'TANGGAL DAFTAR',
        ];
    }

    public function map($user): array
    {
        static $row = 0;
        $row++;

        return [
            $row,
            strtoupper($user->name),
            $user->email,
            $user->phone ?? '-',
            strtoupper($user->role),
            $user->id_pengenal_siswa ?? '-',
            $user->is_suspended ? 'TERTANGGUH (SUSPENDED)' : 'AKTIF',
            $user->borrow_limit . ' BUKU',
            $user->created_at->format('d/m/Y H:i'),
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
