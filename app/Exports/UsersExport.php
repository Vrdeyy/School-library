<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return User::query()->orderBy('name');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Role',
            'NIS/NIP',
            'Status',
            'Limits',
            'Registered At',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->phone,
            $user->role,
            $user->nis,
            $user->is_suspended ? 'Suspended' : 'Active',
            $user->borrow_limit,
            $user->created_at->format('Y-m-d H:i'),
        ];
    }
}
