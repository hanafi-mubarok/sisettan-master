<?php

namespace App\Http\Controllers;

use App\Exports\PenawaransExport;
use App\Exports\PenawaransTemplateExport;
use App\Http\Requests\ImportPenawaranRequest;
use App\Models\Penawaran;
use App\Http\Requests\StorePenawaranRequest;
use App\Http\Requests\UpdatePenawaranRequest;
use App\Imports\PenawaransImport;
use App\Models\Daerah;
use App\Models\Daftar;
use App\Models\Kecamatan;
use App\Models\Notification as NotificationModel;
use App\Models\StatusPelelang;
use App\Models\Tahun;
use App\Models\Tkd;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PenawaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:daftar.index')->only('index');
        $this->middleware('permission:daftar.create')->only('create');
        $this->middleware('permission:daftar.edit')->only('edit', 'update');
        $this->middleware('permission:daftar.destroy')->only('destroy');
    }

    public function index(Request $request)
    {


        $tkds = Tkd::all();
        $harga_dasar = $request->input('harga_dasar');
        $nama = $request->input('nama');
        // $tahunId = session('tahun_id');
        $selectedTahunId = session('selected_tahun_id');
        $tahunSelected = Tahun::where('id', $selectedTahunId)->value('tahun');
        $daftarIdFromSession = (int) session('selected_kelurahan_id');
        $kelurahanIdFromDaerah = Daerah::where('id_kelurahan', $daftarIdFromSession)
            ->whereYear('tanggal_lelang', $tahunSelected)
            ->pluck('id_kelurahan')->first();

        $tkdDropdown = DB::table('barang_yang_dilelang')
            ->select('id', 'lokasi', 'kategori')
            ->where('id_branch', $kelurahanIdFromDaerah)
            ->whereNull('deleted_at')
            ->orderBy('lokasi', 'ASC')
            ->get();
        // dd($request->input('tkdsearch'));
        $daftarList = Daftar::select(
            'daftars.id',
            'daftars.id_kelurahan',
            'daftars.no_urut',
            'daftars.nama',
            'daftars.alamat',
            'daftars.no_kk',
            'daftars.no_wp',
            'daftars.tgl_perjanjian',
            'kelurahans.kelurahan'
        )
            ->leftJoin('kelurahans', 'daftars.id_kelurahan', '=', 'kelurahans.id')
            ->where('daftars.id_kelurahan', $kelurahanIdFromDaerah)
            ->whereNull('daftars.deleted_at')
            ->orderByRaw("CAST(daftars.no_urut AS SIGNED) ASC")
            ->get();

        $statusPelelangs = StatusPelelang::orderBy('status_pelelang')->get();

        $penawarans = DB::table('penawarans')
            ->leftJoin('barang_yang_dilelang', 'penawarans.idfk_barang', '=', 'barang_yang_dilelang.id')
            ->leftJoin('status_pelelangs', 'penawarans.status_pelelang_id', '=', 'status_pelelangs.id')
            ->select(
                'penawarans.id',
                'penawarans.id_penawaran',
                'penawarans.id_user',
                'penawarans.name',
                'penawarans.idfk_barang',
                'penawarans.aset_id',
                'barang_yang_dilelang.nama_barang',
                'barang_yang_dilelang.merk',
                'barang_yang_dilelang.lokasi',
                'barang_yang_dilelang.harga_dasar',
                'barang_yang_dilelang.kelipatan',
                'barang_yang_dilelang.tgl_akhir_penawaran',
                'penawarans.status_pelelang_id',
                'status_pelelangs.status_pelelang',
                'penawarans.nilai_penawaran',
                'penawarans.keterangan',
                'penawarans.gugur'
            )
            ->when($request->input('tkdsearch'), function ($query, $tkdsearchID) {
                return $query->where('penawarans.idfk_barang', $tkdsearchID);
            })
            ->when($nama, function ($query, $nama) {
                return $query->where('penawarans.name', 'like', '%' . $nama . '%');
            })
            ->whereNull('penawarans.deleted_at')
            ->orderBy('barang_yang_dilelang.nama_barang')
            ->orderBy('penawarans.nilai_penawaran')
            ->simplePaginate(10);

        $tkdSelected = $request->input('bukti');
        return view('lelang.penawaran.index')->with([
            'penawarans' => $penawarans,
            'tkds' => $tkds,
            'tkdSelected' => $tkdSelected,
            'harga_dasar' => $harga_dasar,
            'daftarList' => $daftarList,
            'tkdDropdown' => $tkdDropdown,
            'statusPelelangs' => $statusPelelangs,
            'nama' => $nama,
        ]);
    }

    public function handleForm(Request $request)
    {
        $request->validate([
            'penawaran' => 'required',
        ]);
        session(['penawaran_id' => $request->penawaran]);
        return redirect()->route('penawaran.create');
    }

    public function toggleGugur($id)
    {
        $penawaran = Penawaran::findOrFail($id);
        $penawaran->gugur = !$penawaran->gugur;
        $penawaran->save();
        return redirect()->back()->with('success', 'Status gugur berhasil diubah');
    }

    public function updateStatusPelelang(Request $request, Penawaran $penawaran)
    {
        $request->validate([
            'gugur' => 'nullable|integer|exists:status_pelelangs,id',
        ]);

        $penawaran->gugur = $request->input('gugur');
        $penawaran->save();

        return redirect()->back()->with('success', 'Status nilai berhasil diubah');
    }


    public function create(Request $request)
    {
        $selectedTahunId = session('selected_tahun_id');
        $tahunSelected = Tahun::where('id', $selectedTahunId)->value('tahun');
        $daftarIdFromSession = (int) session('selected_kelurahan_id');
        $kelurahanIdFromDaerah = Daerah::where('id_kelurahan', $daftarIdFromSession)
            ->whereYear('tanggal_lelang', $tahunSelected)
            ->pluck('id_kelurahan')->first();

        $penawaranId = session('penawaran_id');
        $tkds = DB::table('barang_yang_dilelang')
            ->select(
                'barang_yang_dilelang.id',
                'barang_yang_dilelang.id_branch',
                'barang_yang_dilelang.kategori',
                'barang_yang_dilelang.merk',
                'barang_yang_dilelang.lokasi',
                'barang_yang_dilelang.harga_dasar',
                'barang_yang_dilelang.kelipatan',
                DB::raw('COALESCE((SELECT nilai_penawaran
                FROM penawarans
                WHERE idfk_barang = barang_yang_dilelang.id
                ORDER BY nilai_penawaran DESC
                LIMIT 1 OFFSET 1), null) AS nilai_penawaran')
            )
            ->where('id_branch', $kelurahanIdFromDaerah)
            ->whereNull('barang_yang_dilelang.deleted_at')
            ->when($request->input('tkd'), function ($query, $tkd) {
                return $query->where('barang_yang_dilelang.lokasi', 'like', '%' . $tkd . '%');
            })
            ->get();

        $daftars = Daftar::where('id', $penawaranId)->first();
        return view('lelang.penawaran.create')->with([
            'tkds' => $tkds,
            'daftars' => $daftars,
        ]);
    }

    public function getTkd(Request $request)
    {
        $tkd = Tkd::where('id', $request->id)->first();

        return response()->json([
            'luas' => $tkd->luas,
            'harga_dasar' => $tkd->harga_dasar
        ]);
    }

    public function store(StorePenawaranRequest $request)
    {
        $user = $request->user();
        $barangId = $request->input('idfk_barang', $request->input('idfk_tkd'));
        $barang = Tkd::findOrFail($barangId);

        DB::flushQueryLog();
        DB::enableQueryLog();

        Log::info('Penawaran store request received', [
            'user_id' => $user->id ?? null,
            'barang_id' => $barang->id ?? null,
            'nilai_penawaran' => $request->input('nilai_penawaran'),
            'keterangan' => $request->input('keterangan'),
            'payload' => $request->except(['_token']),
        ]);

        $penawaran = Penawaran::create([
            'id_penawaran' => $this->buildPenawaranId($user, $barang),
            'id_user' => $user->id,
            'name' => $user->name,
            'idfk_barang' => $barang->id,
            'aset_id' => $request->input('aset_id', $barang->aset_id ?? $barang->lokasi ?? $barang->id),
            'nilai_penawaran' => $request->input('nilai_penawaran'),
            'keterangan' => $request->input('keterangan'),
            'gugur' => 0,
        ]);

        NotificationModel::create([
            'judul' => 'ada penawaran baru',
            'detail' => $penawaran->name . ' menawar ' . ($barang->nama_barang ?? '-') . ' dengan penawaran Rp.' . number_format((int) $penawaran->nilai_penawaran, 0, ',', '.') . ' cek sekarang!',
            'link' => url('/lelang/' . $barang->id),
            'role' => 'admin_branch',
            'user_id' => null,
            'is_read' => false,
        ]);

        $message = 'Penawaran berhasil disimpan.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $penawaran,
            ]);
        }

            Log::info('Penawaran store saved', [
                'penawaran_id' => $penawaran->id ?? null,
                'attributes' => $penawaran->toArray(),
                'queries' => DB::getQueryLog(),
            ]);

        return redirect()
            ->route('dashboard.lelang.detail', $barang->id)
            ->with('success', $message);
    }

    public function show(Penawaran $penawaran)
    {
        return redirect()->route('penawaran.index');
    }

    private function buildPenawaranId($user, Tkd $barang): string
    {
        return $user->id . 'X' . $barang->id;
    }

    public function edit(Penawaran $penawaran)
    {
        $selectedTahunId = session('selected_tahun_id');
        $tahunSelected = Tahun::where('id', $selectedTahunId)->value('tahun');
        $daftarIdFromSession = (int) session('selected_kelurahan_id');
        $kelurahanIdFromDaerah = Daerah::where('id_kelurahan', $daftarIdFromSession)
            ->whereYear('tanggal_lelang', $tahunSelected)
            ->pluck('id_kelurahan')->first();

        $tkds = Tkd::where('id_branch', $kelurahanIdFromDaerah)->get();
        $daftars = Daftar::where('id_kelurahan', $kelurahanIdFromDaerah)->get();
        $idfkTkd = $penawaran->idfk_barang;
        $tkdList = Tkd::where('id', $idfkTkd)->first();
        return view('lelang.penawaran.edit',)
            ->with([
                'tkds' => $tkds,
                'daftars' => $daftars,
                'penawaran' => $penawaran,
                'tkdList' => $tkdList,
            ]);
    }

    public function update(UpdatePenawaranRequest $request, Penawaran $penawaran)
    {
        $penawaran->update($request->validated());
        return redirect()->route('penawaran.index')->with('success', 'Penawaran updated successfully.');
    }

    public function destroy(Penawaran $penawaran)
    {
        $penawaran->delete();

        return redirect()->route('penawaran.index')->with('success', 'Penawaran deleted successfully.');
    }


    public function import(ImportPenawaranRequest $request)
    {
        Excel::import(new PenawaransImport, $request->file('import-file')->store('import-files'));
        return redirect()->route('penawaran.index')->with('success', 'Tambahkan Data Penawaran Sukses diimport');
    }

    public function export()
    {
        return Excel::download(new PenawaransExport, 'Penawaran.xlsx');
    }

    public function template()
    {
        return Excel::download(new PenawaransTemplateExport, 'Penawaran_template.xlsx');
    }

    public function deleteAll()
    {
        Penawaran::truncate(); // Menghapus semua baris pada tabel

        return redirect()->back()->with('success', 'Semua data berhasil dihapus.');
    }

    public function downloadTemplate()
    {
        $templatePath = public_path('Excel/templates/penawaran_template.xlsx');
        if (!file_exists($templatePath)) {
            return redirect()->route('penawaran.index')->with('error', 'Template file not found.');
        }

        return response()->download($templatePath, 'penawaran_template.xlsx');
    }

    // public function cetakLuas()
    // {
    // $luass = Penawaran::all();

    // $pdf = PDF::loadview('lelang.penawaran.luas', ['luass' => $luass]);
    // return $pdf->stream();
    // }

    public function cetakTidakLaku()
    {
        $selectedTahunId = session('selected_tahun_id');
        $tahunSelected = Tahun::where('id', $selectedTahunId)->value('tahun');
        $daftarIdFromSession = (int) session('selected_kelurahan_id');
        $kelurahanIdFromDaerah = Daerah::where('id_kelurahan', $daftarIdFromSession)
            ->whereYear('tanggal_lelang', $tahunSelected)
            ->pluck('id_kelurahan')->first();

        // dd($kelurahanIdFromDaerah);
        $daerahList = Daerah::withTrashed()
            ->where('daerahs.id_kelurahan', $kelurahanIdFromDaerah)
            ->select(
                'kelurahans.kelurahan',
            )
            ->leftJoin('tahuns', 'tahuns.id', 'daerahs.thn_sts')
            ->leftJoin('kelurahans', 'kelurahans.id', 'daerahs.id_kelurahan')
            ->first();

        $penawarans = DB::table('barang_yang_dilelang')
            ->leftJoin('penawarans', 'barang_yang_dilelang.id', '=', 'penawarans.idfk_barang')
            ->leftJoin('kelurahans', 'barang_yang_dilelang.id_branch', '=', 'kelurahans.id')
            ->select(
                'barang_yang_dilelang.id',
                'barang_yang_dilelang.lokasi',
                'barang_yang_dilelang.merk',
                'barang_yang_dilelang.kategori',
                'barang_yang_dilelang.harga_dasar',
                'barang_yang_dilelang.kelipatan'
            )
            ->whereNull('penawarans.idfk_barang')
            ->whereNull('penawarans.deleted_at')
            ->where('barang_yang_dilelang.id_branch', $kelurahanIdFromDaerah)
            ->get();


        $pdf = PDF::loadview('lelang.penawaran.tidak-laku', [
            'penawarans' => $penawarans,
            'daerahList' => $daerahList,
        ]);
        return $pdf->stream();
    }

    public function cetakBA()
    {
        $selectedTahunId = session('selected_tahun_id');
        $tahunSelected = Tahun::where('id', $selectedTahunId)->value('tahun');
        $daftarIdFromSession = (int) session('selected_kelurahan_id');
        $kelurahanIdFromDaerah = Daerah::where('id_kelurahan', $daftarIdFromSession)
            ->whereYear('tanggal_lelang', $tahunSelected)
            ->pluck('id_kelurahan')->first();

        $daerahList = Daerah::withTrashed()
            ->where('main.id_kelurahan', $daftarIdFromSession)
            ->select(
                'main.periode',
                'kelurahans.kelurahan',
                'main.noba',
            )
            ->from('daerahs as main')
            ->leftJoin('tahuns', 'tahuns.id', 'main.thn_sts')
            ->leftJoin('kelurahans', 'kelurahans.id', 'main.id_kelurahan')
            ->first();

        $sub = Penawaran::select(
            'idfk_barang',
            DB::raw('MAX(CAST(penawarans.nilai_penawaran AS UNSIGNED)) as max_penawaran')
        )
            ->whereNull('deleted_at')
            ->where('gugur', '=', false)
            ->groupBy('idfk_barang');

        $penawaran = Penawaran::select(
            'daftars.no_urut',
            'daftars.nama',
            'daftars.tgl_perjanjian',
            'barang_yang_dilelang.lokasi',
            'barang_yang_dilelang.merk',
            'barang_yang_dilelang.kategori',
            'barang_yang_dilelang.harga_dasar',
            'barang_yang_dilelang.kelipatan',
            'penawarans.id',
            'penawarans.nilai_penawaran',
            'penawarans.idfk_barang',
            'penawarans.id_user',
            DB::raw('
            (SELECT nilai_penawaran
            FROM penawarans AS subquery
            WHERE subquery.idfk_barang = penawarans.idfk_barang
            AND subquery.nilai_penawaran IS NOT NULL
            ORDER BY subquery.nilai_penawaran DESC
            LIMIT 1 OFFSET 1) AS nilai_penawaran2
            '),
            
        )
            ->joinSub($sub, 'subquery', function ($join) {
                $join->on('penawarans.idfk_barang', '=', 'subquery.idfk_barang')
                    ->on('penawarans.nilai_penawaran', '=', 'subquery.max_penawaran');
            })
            ->leftJoin('barang_yang_dilelang', 'barang_yang_dilelang.id', '=', 'penawarans.idfk_barang')
            ->leftJoin('users', 'penawarans.id_user', '=', 'users.id')
            ->leftJoin('daftars', 'users.username', '=', 'daftars.id_daftar')
            ->where('daftars.id_kelurahan', $kelurahanIdFromDaerah)
            ->orderBy('barang_yang_dilelang.lokasi', 'DESC')
            ->get();

        $pdf = PDF::loadView('lelang.penawaran.cetak-ba', [
            'penawarans' => $penawaran,
            'daerahList' => $daerahList,
        ]);

        return $pdf->stream('cetak-ba.pdf');
    }

    public function cetakSekota()
    {
        $cetakSekota = Tkd::select(
            'kelurahans.kelurahan',
            DB::raw('COUNT(DISTINCT barang_yang_dilelang.id) as total_bidang'),
            DB::raw('SUM(barang_yang_dilelang.kelipatan) as total_luas'),
            DB::raw('SUM(barang_yang_dilelang.harga_dasar) as total_harga_dasar'),
            DB::raw(
                '(
                    SELECT SUM(p.nilai_penawaran)
                    FROM penawarans p
                    LEFT JOIN barang_yang_dilelang t ON t.id = p.idfk_barang
                    WHERE t.id_branch = kelurahans.id
                )
            as total_nilai_penawaran'
            ),
            DB::raw(
                '(
                    SELECT count(p.id)
                    FROM penawarans p
                    LEFT JOIN barang_yang_dilelang t ON t.id = p.idfk_barang
                    WHERE t.id_branch = kelurahans.id
                )
            as total_penawaran'
            ),
            DB::raw(
                '(
                    SELECT COUNT(d.id)
                    FROM daftars d
                    WHERE d.id_kelurahan = kelurahans.id
                )
            as total_daftar'
            ),
            DB::raw('(SELECT COUNT(DISTINCT t.id) FROM barang_yang_dilelang t
            LEFT JOIN penawarans p ON t.id = p.idfk_barang
            WHERE p.idfk_barang IS NULL AND t.id_branch = kelurahans.id) as total_tidak_laku')
        )
            ->leftJoin('kelurahans', 'barang_yang_dilelang.id_branch', '=', 'kelurahans.id')
            ->groupBy('kelurahans.id')
            ->get();

        $cetakSekotaKecamatan = Tkd::select(
            'kecamatans.kecamatan',
            DB::raw('SUM(barang_yang_dilelang.kelipatan) as total_luas'),
            DB::raw('SUM(barang_yang_dilelang.harga_dasar) as total_harga_dasar'),
            DB::raw(
                '(
                    SELECT SUM(p.nilai_penawaran)
                    FROM penawarans p
                    LEFT JOIN barang_yang_dilelang t ON t.id = p.idfk_barang
                        LEFT JOIN kelurahans k ON t.id_branch = k.id
                    WHERE k.id_kecamatan = kecamatans.id
                ) as total_nilai_penawaran'
            )
        )
            ->leftJoin('kelurahans', 'barang_yang_dilelang.id_branch', '=', 'kelurahans.id')
            ->leftJoin('kecamatans', 'kelurahans.id_kecamatan', '=', 'kecamatans.id')
            ->groupBy('kelurahans.id_kecamatan')
            ->get();
        $pdf = PDF::loadview(
            'lelang.penawaran.rekap-sekota',
            [
                'cetakSekota' => $cetakSekota,
                'cetakSekotaKecamatan' => $cetakSekotaKecamatan,
            ]
        );
        return $pdf->stream();
    }
}

