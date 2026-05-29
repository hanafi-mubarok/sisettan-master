<?php

namespace App\Http\Controllers;

use App\Models\Daerah;
use App\Models\Kelurahan;
use App\Models\Tahun;
use App\Models\Tkd;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    public function index()
    {

        $kelurahans = Kelurahan::all();
        $selectedTahunId = session('selected_tahun_id');
        $tahunSelected = Tahun::where('id', $selectedTahunId)->value('tahun');
        $daftarIdFromSession = (int) session('selected_kelurahan_id');
        $kelurahanIdFromDaerah = Daerah::where('id_kelurahan', $daftarIdFromSession)
            ->whereYear('tanggal_lelang', $tahunSelected)
            ->pluck('id_kelurahan')->first();

        $tkd = Tkd::select(
            'barang_yang_dilelang.id',
            'barang_yang_dilelang.id_branch',
            'barang_yang_dilelang.bidang',
            'barang_yang_dilelang.letak',
            'barang_yang_dilelang.bukti',
            'barang_yang_dilelang.harga_dasar',
            'barang_yang_dilelang.luas',
            'barang_yang_dilelang.keterangan',
            'barang_yang_dilelang.nop',
            'barang_yang_dilelang.longitude',
            'barang_yang_dilelang.latitude',
            'barang_yang_dilelang.foto',
            'kelurahans.kelurahan'
        )
            ->leftJoin('kelurahans', 'barang_yang_dilelang.id_branch', '=', 'kelurahans.id')
            ->where('barang_yang_dilelang.id_branch', $kelurahanIdFromDaerah)
            ->whereNull('barang_yang_dilelang.deleted_at')
            ->get();


        return view('maps.detail.index')
            ->with([
                'tkd' => $tkd,
                'kelurahans' => $kelurahans,
            ]);
    }

    public function detail($id)
    {
        $tkd = Tkd::select(
            'barang_yang_dilelang.id',
            'barang_yang_dilelang.id_branch',
            'barang_yang_dilelang.bidang',
            'barang_yang_dilelang.letak',
            'barang_yang_dilelang.bukti',
            'barang_yang_dilelang.harga_dasar',
            'barang_yang_dilelang.luas',
            'barang_yang_dilelang.keterangan',
            'barang_yang_dilelang.nop',
            'barang_yang_dilelang.longitude',
            'barang_yang_dilelang.latitude',
            'barang_yang_dilelang.latitude',
            'barang_yang_dilelang.foto',
            'kelurahans.kelurahan',
            'kecamatans.kecamatan'
        )
            ->leftJoin('kelurahans', 'barang_yang_dilelang.id_branch', '=', 'kelurahans.id')
            ->leftJoin('kecamatans', 'kelurahans.id_kecamatan', '=', 'kecamatans.id')
            ->where('barang_yang_dilelang.id', $id)
            ->first();
        return response()->json($tkd, 200);
    }
}

