<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class UsersImport implements ToModel, WithHeadingRow, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'username' => $row['username'] ?? $row['user_name'] ?? null,
            'name' => $row['nama'] ?? $row['name'] ?? null,
            'email' => $row['email'] ?? null,
            'phone' => $row['no_hp'] ?? $row['phone'] ?? null,
            'address' => $row['alamat'] ?? $row['address'] ?? null,
            'role_name' => $row['role_name'] ?? $row['role'] ?? 'user',
            'is_karyawan' => $this->toBoolean($row['is_karyawan'] ?? $row['karyawan'] ?? false),
            'isverified' => 0,
            'password' => Hash::make($row['password'] ?? ''),
        ]);
    }

    public function uniqueBy()
    {
        return 'email';
    }

    private function toBoolean($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value === 1;
        }

        $normalized = strtolower(trim((string) $value));

        return in_array($normalized, ['1', 'true', 'yes', 'y', 'ya'], true);
    }
}