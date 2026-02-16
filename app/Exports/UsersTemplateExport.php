<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersTemplateExport implements WithHeadings
{
    public function headings(): array
    {
        return [
            'name',
            'phone',
            'id_pengenal_siswa',
            'kelas',
            'jurusan',
            'angkatan',
            'pin',
            'borrow_limit',
        ];
    }
}
