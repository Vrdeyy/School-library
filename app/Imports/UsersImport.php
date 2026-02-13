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
        // Handle mapping for 'id_pengenal_siswa' because export might use 'ID Pengenal Siswa' or 'id_pengenal_siswa'
        $id_pengenal_siswa = $row['id_pengenal_siswa'] ?? $row['id_pengenal_siswa'] ?? $row['nis'] ?? $row['nisnip'] ?? $row['id_nis'] ?? null;
        
        // Handle password: if missing in file, use random for user (they use PIN) or default for admin
        $role = $row['role'] ?? 'user';
        $password = $row['password'] ?? null;
        
        if (empty($password)) {
            $password = ($role === 'admin') ? '12345678' : \Illuminate\Support\Str::random(16);
        }
        
        $hashedPassword = Hash::make($password);

        return new User([
            'name'         => $row['name'],
            'email'        => $row['email'],
            'phone'        => isset($row['phone']) ? (string)$row['phone'] : null,
            'password'     => $hashedPassword,
            'role'         => $row['role'] ?? 'user',
            'kelas'        => $row['kelas'] ?? null,
            'jurusan'      => $row['jurusan'] ?? null,
            'angkatan'     => $row['angkatan'] ?? null,
            'id_pengenal_siswa' => $id_pengenal_siswa !== null ? (string)$id_pengenal_siswa : null,
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
            'id_pengenal_siswa' => 'nullable',
        ];
    }
}
