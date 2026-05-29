<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select('email', 'name', 'phone', 'address', 'role_name', 'isverified', 'created_at')->get();
    }
    public function headings(): array
    {
        return [
            'Email',
            'Nama',
            'No. HP',
            'Alamat',
            'Role',
            'Verifikasi',
            'Created At',
        ];
    }
}
