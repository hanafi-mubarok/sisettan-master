<?php

namespace App\Imports;

use App\Models\Jabatan;
use App\Models\Branch;
use App\Models\Pejabat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class PejabatsImport implements ToModel, WithHeadingRow, WithUpserts
{
    protected $jabatans;
    protected $branches;

    public function __construct()
    {
        $this->jabatans = Jabatan::select('id', 'jabatan')->get();
        $this->branches = Branch::select('id', 'branch')->get();
    }

    public function model(array $row)
    {
        $jabatanName = trim((string) ($row['jabatan'] ?? ''));
        $branchName = trim((string) ($row['branch'] ?? ''));

        $jabatan = $this->jabatans->first(function ($item) use ($jabatanName) {
            return strcasecmp(trim((string) $item->jabatan), $jabatanName) === 0;
        });

        $branch = $this->branches->first(function ($item) use ($branchName) {
            return strcasecmp(trim((string) $item->branch), $branchName) === 0;
        });

        if (!$jabatan) {
            $jabatan = Jabatan::firstOrCreate(['jabatan' => $jabatanName]);
            $this->jabatans->push($jabatan);
        }

        if (!$branch && $branchName !== '') {
            $branch = Branch::firstOrCreate(['branch' => $branchName]);
            $this->branches->push($branch);
        }

        return new Pejabat([
            'id_jabatan' => $jabatan->id ?? null,
            'id_branch' => $branch->id ?? null,
            'nama_karyawan' => $row['nama_karyawan'] ?? $row['nama_pejabat'] ?? null,
            'nik' => $row['nik'] ?? $row['nip'] ?? null,
            'gender' => $row['gender'] ?? $row['position'] ?? $row['no_sk'] ?? null,
        ]);
    }

    public function headings(): array
    {
        return [
            'Jabatan',
            'Branch',
            'Nama Karyawan',
            'NIK',
            'Gender',
        ];
    }

    public function uniqueBy()
    {
        return 'nik';
    }
}
