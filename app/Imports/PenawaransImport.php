<?php

namespace App\Imports;

use App\Models\Daftar;
use App\Models\Penawaran;
use App\Models\Tkd;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PenawaransImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $penawaransData = [];

        foreach ($rows as $row) {
            $idPenawaran = $row['id_penawaran'] ?? null;
            $nilaiPenawaran = $row['nilai_penawaran'] ?? null;
            $keterangan = $row['keterangan'] ?? null;
            $gugur = $this->toBoolean($row['gugur'] ?? 0);
            $statusPelelangId = $row['status_pelelang_id'] ?? null;

            $idUser = $row['id_user'] ?? null;
            $name = $row['name'] ?? null;

            // Backward compatibility: old template used id_daftar.
            if (!$idUser && !empty($row['id_daftar'])) {
                $daftar = Daftar::where('id_daftar', $row['id_daftar'])->first();
                if ($daftar) {
                    $user = User::where('username', $row['id_daftar'])
                        ->orWhere('name', $daftar->nama)
                        ->first();
                    $idUser = $user->id ?? null;
                    $name = $name ?? ($user->name ?? $daftar->nama ?? null);
                }
            }

            $idfkBarang = $row['idfk_barang'] ?? null;
            $asetId = $row['aset_id'] ?? null;

            // Backward compatibility: old template used id_tkd.
            if (!$idfkBarang && !empty($row['id_tkd'])) {
                $tkd = Tkd::where('id_tkd', $row['id_tkd'])->first();
                if ($tkd) {
                    $idfkBarang = $tkd->id;
                    $asetId = $asetId ?? ($tkd->aset_id ?? $row['id_tkd']);
                }
            }

            if (!$idPenawaran && $idUser && $idfkBarang) {
                $idPenawaran = $idUser . 'X' . $idfkBarang;
            }

            if (!$idPenawaran) {
                continue;
            }

            $penawaransData[] = [
                'id_penawaran' => $idPenawaran,
                'id_user' => $idUser,
                'name' => $name,
                'idfk_barang' => $idfkBarang,
                'aset_id' => $asetId,
                'nilai_penawaran' => $nilaiPenawaran,
                'keterangan' => $keterangan,
                'gugur' => $gugur,
                'status_pelelang_id' => $statusPelelangId,
            ];
        }

        Penawaran::upsert(
            $penawaransData,
            ['id_penawaran'],
            ['id_user', 'name', 'idfk_barang', 'aset_id', 'nilai_penawaran', 'keterangan', 'gugur', 'status_pelelang_id']
        );
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
