<?php

namespace App\Exports;

use App\Models\Tkd;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TkdsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Tkd::select(
            'aset_id',
            'nama_barang',
            'status',
            'kondisi',
            'merk',
            'kategori',
            'lokasi',
            'kelipatan',
            'harga_dasar',
            'tahun',
            'tgl_akhir_penawaran',
            'keterangan'
        )
            ->get()
            ->map(function ($row) {
                return [
                    $row->aset_id ?? '-',
                    $row->nama_barang ?? '-',
                    $row->status ?? '-',
                    $row->kondisi ?? '-',
                    $row->merk ?? '-',
                    $row->kategori ?? '-',
                    $row->lokasi ?? '-',
                    (int) ($row->kelipatan ?? 0),
                    (int) ($row->harga_dasar ?? 0),
                    $row->tahun ?? '-',
                    $row->tgl_akhir_penawaran ? date('d-m-Y H:i', strtotime($row->tgl_akhir_penawaran)) : '-',
                    $row->keterangan ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Aset ID',
            'Nama Barang',
            'Status',
            'Kondisi',
            'Merk',
            'Kategori',
            'Lokasi',
            'Kelipatan',
            'Harga Dasar',
            'Tahun',
            'Tgl Akhir Penawaran',
            'Keterangan',
        ];
    }
}
