<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Handle mapping for 'nis' because export might use 'NIS/NIP' or 'nisnip'
        $nis = $row['nis'] ?? $row['nisnip'] ?? $row['id_nis'] ?? null;
        
        // Handle password: if missing in file, use default
        $password = !empty($row['password']) ? Hash::make($row['password']) : Hash::make('12345678');

        return new User([
            'name'         => $row['name'],
            'email'        => $row['email'],
            'phone'        => isset($row['phone']) ? (string)$row['phone'] : null,
            'password'     => $password,
            'role'         => $row['role'] ?? 'user',
            'kelas'        => $row['kelas'] ?? null,
            'jurusan'      => $row['jurusan'] ?? null,
            'angkatan'     => $row['angkatan'] ?? null,
            'nis'          => $nis !== null ? (string)$nis : null,
            'pin'          => isset($row['pin']) ? (string)$row['pin'] : '123456',
            'borrow_limit' => $row['borrow_limit'] ?? $row['limits'] ?? 3,
            'is_suspended' => false,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|max:20',
            'password' => 'nullable|min:6',
            'role' => 'nullable|in:admin,user',
            'nis' => 'nullable',
        ];
    }
}
