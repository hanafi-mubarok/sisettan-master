<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PenawaransExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $rows = DB::table('penawarans')
            ->leftJoin('barang_yang_dilelang', 'penawarans.idfk_barang', '=', 'barang_yang_dilelang.id')
            ->leftJoin('status_pelelangs', 'penawarans.gugur', '=', 'status_pelelangs.id')
            ->select(
                'penawarans.name',
                'barang_yang_dilelang.nama_barang',
                'barang_yang_dilelang.merk',
                'barang_yang_dilelang.lokasi',
                'barang_yang_dilelang.harga_dasar',
                'barang_yang_dilelang.kelipatan',
                'penawarans.nilai_penawaran',
                'status_pelelangs.status_pelelang',
                'barang_yang_dilelang.tgl_akhir_penawaran'
            )
            ->whereNull('penawarans.deleted_at')
            ->orderBy('barang_yang_dilelang.nama_barang')
            ->orderBy('penawarans.nilai_penawaran')
            ->get();

        return $rows->values()->map(function ($row, $index) {
            return [
                $index + 1,
                $row->name ?? '-',
                $row->nama_barang ?? '-',
                $row->merk ?? '-',
                $row->lokasi ?? '-',
                (int) ($row->harga_dasar ?? 0),
                (int) ($row->kelipatan ?? 0),
                (int) ($row->nilai_penawaran ?? 0),
                $row->status_pelelang ?? '-',
                $row->tgl_akhir_penawaran
                    ? date('d-m-Y H:i', strtotime($row->tgl_akhir_penawaran))
                    : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Nama Barang',
            'Merk',
            'Lokasi',
            'Harga Dasar',
            'Kelipatan',
            'Nilai Penawaran',
            'Status Nilai',
            'Tgl Akhir Penawaran',
        ];
    }
}
