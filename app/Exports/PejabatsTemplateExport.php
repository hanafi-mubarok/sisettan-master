<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PejabatsTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return collect();
    }

    public function headings(): array
    {
        return [
            'jabatan',
            'branch',
            'nama_karyawan',
            'nik',
            'gender',
        ];
    }
}