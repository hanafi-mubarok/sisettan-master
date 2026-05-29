<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return collect();
    }

    public function headings(): array
    {
        return [
            'email',
            'nama',
            'no_hp',
            'alamat',
            'role_name',
            'is_karyawan',
            'password',
        ];
    }
}