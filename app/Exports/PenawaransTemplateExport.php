<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenawaransTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return collect();
    }

    public function headings(): array
    {
        return [
            'id_penawaran',
            'id_user',
            'name',
            'idfk_barang',
            'aset_id',
            'nilai_penawaran',
            'gugur',
            'status_pelelang_id',
            'keterangan',
        ];
    }
}
