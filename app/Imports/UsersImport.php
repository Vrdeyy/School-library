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
        // 1. Format Name
        $name = trim($row['name']);
        
        // 2. Email (nullable now)
        $email = isset($row['email']) && !empty($row['email']) ? trim($row['email']) : null;

        // 3. ID Pengenal Siswa (Check common column variations)
        $id_pengenal_siswa = $row['id_pengenal_siswa'] ?? $row['nis'] ?? $row['id'] ?? null;
        
        // 4. Role is always 'user'
        $role = 'user';
        
        // 5. Password/PIN handling
        $pin = isset($row['pin']) ? (string)$row['pin'] : '123456';

        return new User([
            'name'              => $name,
            'email'             => $email,
            'phone'             => isset($row['phone']) ? (string)$row['phone'] : null,
            'password'          => null, // Regular users use PIN
            'role'              => $role,
            'kelas'             => $row['kelas'] ?? null,
            'jurusan'           => $row['jurusan'] ?? null,
            'angkatan'          => $row['angkatan'] ?? null,
            'id_pengenal_siswa' => $id_pengenal_siswa !== null ? (string)$id_pengenal_siswa : null,
            'pin'               => $pin,
            'borrow_limit'      => $row['borrow_limit'] ?? 3,
            'is_suspended'      => false,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|max:20',
            'id_pengenal_siswa' => 'required', // Now required for user creation
        ];
    }
}
