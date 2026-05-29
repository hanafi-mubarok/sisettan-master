<?php

namespace App\Exports;

use App\Models\Pejabat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PejabatsExport implements FromCollection,  WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Pejabat::select('midi_employee.nama_karyawan', 'jabatans.jabatan', 'midi_employee.nik', 'midi_employee.gender')
            ->join('jabatans', 'midi_employee.id_jabatan', '=', 'jabatans.id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Jabatan',
            'NIK',
            'Gender'
        ];
    }
}
