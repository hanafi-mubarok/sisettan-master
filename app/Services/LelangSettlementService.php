<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Penawaran;
use App\Models\Tkd;
use Illuminate\Support\Facades\DB;

class LelangSettlementService
{
    public function settleExpiredByBarangId(int $barangId): bool
    {
        $tkd = Tkd::query()
            ->select('id', 'tgl_akhir_penawaran')
            ->where('id', $barangId)
            ->first();

        if (!$tkd || !$this->isExpired($tkd->tgl_akhir_penawaran)) {
            return false;
        }

        return $this->settleByBarangId($barangId);
    }

    public function settleAllExpired(): int
    {
        $processed = 0;

        $expiredBarangIds = Tkd::query()
            ->whereNotNull('tgl_akhir_penawaran')
            ->where('tgl_akhir_penawaran', '<=', now())
            ->pluck('id');

        foreach ($expiredBarangIds as $barangId) {
            if ($this->settleByBarangId((int) $barangId)) {
                $processed++;
            }
        }

        return $processed;
    }

    public function settleByBarangId(int $barangId): bool
    {
        return (bool) DB::transaction(function () use ($barangId) {
            $tkd = Tkd::query()
                ->whereKey($barangId)
                ->lockForUpdate()
                ->first();

            if (!$tkd) {
                return false;
            }

            $wasUpdated = false;

            if (strtoupper((string) ($tkd->status ?? '')) !== 'CLOSED') {
                $tkd->status = 'CLOSED';
                $tkd->save();
                $wasUpdated = true;
            }

            $winner = Penawaran::query()
                ->where('idfk_barang', $barangId)
                ->orderByDesc('nilai_penawaran')
                ->orderBy('created_at')
                ->orderBy('id')
                ->lockForUpdate()
                ->first();

            if (!$winner) {
                return $wasUpdated;
            }

            Penawaran::query()
                ->where('idfk_barang', $barangId)
                ->update(['gugur' => 1]);

            Penawaran::query()
                ->whereKey($winner->id)
                ->update(['gugur' => 2]);

            $this->notifyWinner($winner, $barangId);

            return true;
        });
    }

    private function notifyWinner(Penawaran $winner, int $barangId): void
    {
        $judul = 'Selamat Anda Terpilih Menjadi Pemenang Lelang';
        $detail = 'Hubungi Whatsapp Panitia https://wa.me/62895411305226 untuk proses lebih lanjut';
        $link = url('/lelang/' . $barangId);

        $alreadyNotified = Notification::query()
            ->where('role', 'user')
            ->where('user_id', $winner->id_user)
            ->where('judul', $judul)
            ->where('link', $link)
            ->exists();

        if ($alreadyNotified) {
            return;
        }

        Notification::create([
            'judul' => $judul,
            'detail' => $detail,
            'link' => $link,
            'role' => 'user',
            'user_id' => $winner->id_user,
            'is_read' => false,
        ]);
    }

    private function isExpired($tglAkhirPenawaran): bool
    {
        if (empty($tglAkhirPenawaran)) {
            return false;
        }

        return strtotime((string) $tglAkhirPenawaran) <= time();
    }
}
