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
        
        // 2. Auto-generate Email if not provided (e.g. "Ujang Ejep" -> "ujang@perpus.com")
        $email = $row['email'] ?? null;
        if (empty($email)) {
            $firstName = explode(' ', $name)[0];
            $baseEmail = strtolower($firstName) . '@perpus.com';
            
            // Basic conflict check (if already exists, append random/ID)
            if (User::where('email', $baseEmail)->exists()) {
                $baseEmail = strtolower(str_replace(' ', '', $name)) . '@perpus.com';
            }
            $email = $baseEmail;
        }

        // 3. Handle ID Pengenal Siswa
        $id_pengenal_siswa = $row['id_pengenal_siswa'] ?? $row['nis'] ?? $row['nisnip'] ?? $row['id_nis'] ?? null;
        
        // 4. Role is always 'user' because admins shouldn't be created via Excel
        $role = 'user';
        
        // 5. Password can be null for regular users
        $hashedPassword = null;

        return new User([
            'name'         => $name,
            'email'        => $email,
            'phone'        => isset($row['phone']) ? (string)$row['phone'] : null,
            'password'     => $hashedPassword,
            'role'         => $role,
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
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|max:20',
            'id_pengenal_siswa' => 'nullable',
        ];
    }
}
