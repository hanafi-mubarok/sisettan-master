<?php

namespace App\Http\Controllers;

use App\Models\Tkd;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    public function index()
    {
        // Query active auctions - status OPEN dan belum melewati deadline
        $lelangItems = Tkd::query()
            ->select(
                'barang_yang_dilelang.id',
                'barang_yang_dilelang.nama_barang',
                'barang_yang_dilelang.kategori',
                'barang_yang_dilelang.merk',
                'barang_yang_dilelang.harga_dasar',
                'barang_yang_dilelang.foto',
                'barang_yang_dilelang.tgl_akhir_penawaran',
                'barang_yang_dilelang.status'
            )
            ->whereNull('barang_yang_dilelang.deleted_at')
            ->where('barang_yang_dilelang.status', 'OPEN')
            ->where('barang_yang_dilelang.tgl_akhir_penawaran', '>', now())
            ->orderBy('barang_yang_dilelang.tgl_akhir_penawaran', 'asc')
            ->limit(12)
            ->get()
            ->map(function ($item) {
                $item->nama_tampil = $item->nama_barang ?: trim(($item->kategori ?: '-') . ' ' . ($item->merk ?: ''));

                $nilaiAngka = (int) preg_replace('/\D/', '', (string) $item->harga_dasar);
                $item->harga_dasar_rupiah = 'Rp ' . number_format($nilaiAngka, 0, ',', '.');
                
                if ($item->foto) {
                    $storagePath = 'storage/' . $item->foto;
                    $publicPath = public_path($storagePath);
                    $item->foto_url = file_exists($publicPath) ? asset($storagePath) : asset('images/gedung_midi.png');
                } else {
                    $item->foto_url = asset('images/gedung_midi.png');
                }
                
                $item->tgl_akhir_penawaran_label = $item->tgl_akhir_penawaran
                    ? date('d M Y H:i', strtotime($item->tgl_akhir_penawaran))
                    : 'Jadwal belum ditentukan';

                return $item;
            });

        // Statistik
        $totalLelang = DB::table('penawarans')
            ->where('gugur', 2)
            ->sum('nilai_penawaran');

        $totalPeserta = DB::table('users')
            ->where('role_name', 'user')
            ->count();

        $asetTerjual = DB::table('barang_yang_dilelang')
            ->where('status', 'CLOSED')
            ->count();

        return view('landing-page', compact('lelangItems', 'totalLelang', 'totalPeserta', 'asetTerjual'));
    }

    public function show(Tkd $tkd)
    {
        $detail = Tkd::query()
            ->select(
                'barang_yang_dilelang.id',
                'barang_yang_dilelang.nama_barang',
                'barang_yang_dilelang.kategori',
                'barang_yang_dilelang.merk',
                'barang_yang_dilelang.lokasi',
                'barang_yang_dilelang.kondisi',
                'barang_yang_dilelang.keterangan',
                'barang_yang_dilelang.kelipatan',
                'barang_yang_dilelang.harga_dasar',
                'barang_yang_dilelang.foto',
                'barang_yang_dilelang.tgl_start_penawaran',
                'barang_yang_dilelang.tgl_akhir_penawaran'
            )
            ->where('barang_yang_dilelang.id', $tkd->id)
            ->whereNull('barang_yang_dilelang.deleted_at')
            ->firstOrFail();

        $detail->nama_tampil = $detail->nama_barang ?: trim(($detail->kategori ?: '-') . ($detail->merk ?: ''));
        $detail->foto_url = $detail->foto
            ? (file_exists(public_path($detail->foto)) ? asset($detail->foto) : asset('storage/' . $detail->foto))
            : asset('images/gedung_midi.png');

        $nilaiAngka = (int) preg_replace('/\D/', '', (string) $detail->harga_dasar);
        $detail->harga_dasar_rupiah = 'Rp ' . number_format($nilaiAngka, 0, ',', '.');
        $detail->tgl_start_penawaran_label = $detail->tgl_start_penawaran
            ? date('d M Y H:i', strtotime($detail->tgl_start_penawaran))
            : 'Jadwal belum ditentukan';
        $detail->tgl_akhir_penawaran_label = $detail->tgl_akhir_penawaran
            ? date('d M Y H:i', strtotime($detail->tgl_akhir_penawaran))
            : 'Jadwal belum ditentukan';

        return view('landing.lelang-detail', compact('detail'));
    }
}

