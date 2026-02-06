<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersTemplateExport implements WithHeadings
{
    public function headings(): array
    {
        return [
            'name',
            'email',
            'phone',
            'password',
            'role',
            'nis',
            'kelas',
            'jurusan',
            'angkatan',
            'pin',
            'borrow_limit',
        ];
    }
}
