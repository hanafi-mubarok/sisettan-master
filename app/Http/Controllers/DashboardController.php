<?php

namespace App\Http\Controllers;

use App\Exports\DaftarsExport;
use App\Http\Requests\ImportDaftarRequest;
use App\Models\Daftar;
use App\Http\Requests\StoreDaftarRequest;
use App\Http\Requests\UpdateDaftarRequest;
use App\Imports\DaftarsImport;
use App\Models\Daerah;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Penawaran;
use App\Models\Profile;
use App\Models\Tahun;
use App\Models\Tkd;
use App\Models\User;
use App\Services\LelangSettlementService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    private LelangSettlementService $lelangSettlementService;

    public function __construct(LelangSettlementService $lelangSettlementService)
    {
        $this->lelangSettlementService = $lelangSettlementService;
    }

    private function isUserRole($user): bool
    {
        $roleName = strtolower((string) ($user->role_name ?? ''));

        return $roleName === 'user' || $user->hasRole('user');
    }

    public function index(Request $request)
    {
        if (Auth::user() && $this->isUserRole(Auth::user())) {
            if ($request->query('mode') === 'riwayat') {
                return $this->riwayatLelang();
            }

            $user = Auth::user();

                // Calculate counts based on the authenticated user's id.
                // Using `id_user` is more reliable than matching names in `daftar`.
                $totalLelangDiikuti = Penawaran::query()
                    ->where('id_user', $user->id)
                    ->whereNull('deleted_at')
                    ->distinct('idfk_barang')
                    ->count('idfk_barang');

                $lelangDimenangkan = Penawaran::query()
                    ->where('id_user', $user->id)
                    ->where('gugur', 0)
                    ->whereNull('deleted_at')
                    ->distinct('idfk_barang')
                    ->count('idfk_barang');

            $statusAkun = $user->isverified ? 'Terverifikasi' : 'Menunggu Verifikasi';

            $lelangAktif = Tkd::query()
                ->select(
                    'id',
                    'nama_barang',
                    'status',
                    'kategori',
                    'merk',
                    'harga_dasar',
                    'foto',
                    'tgl_start_penawaran',
                    'tgl_akhir_penawaran',
                    'lokasi'
                )
                ->whereNull('deleted_at')
                ->where('status', 'OPEN')
                ->where('tgl_akhir_penawaran', '>', now())
                ->latest('id')
                ->limit(6)
                ->get()
                ->map(function ($item) {
                    $item->foto_url = $item->foto ? asset('storage/' . $item->foto) : asset('images/gedung_midi.png');
                    $item->harga_dasar_rupiah = 'Rp ' . number_format((int) ($item->harga_dasar ?? 0), 0, ',', '.');
                    $item->nama_tampil = $item->nama_barang ?: trim(($item->kategori ?: '-') . ' ' . ($item->merk ?: ''));

                    if ($item->tgl_start_penawaran && $item->tgl_akhir_penawaran) {
                        $item->status_waktu = date('d M Y', strtotime($item->tgl_start_penawaran)) . ' - ' . date('d M Y', strtotime($item->tgl_akhir_penawaran));
                    } elseif ($item->tgl_start_penawaran) {
                        $item->status_waktu = 'Mulai ' . date('d M Y', strtotime($item->tgl_start_penawaran));
                    } else {
                        $item->status_waktu = 'Jadwal belum ditentukan';
                    }

                    return $item;
                });

            $notifikasi = $this->getDashboardNotifications($user);

            return view('dashboard.user', compact(
                'totalLelangDiikuti',
                'lelangDimenangkan',
                'statusAkun',
                'lelangAktif',
                'notifikasi'
            ));
        }

        $tahunSelected = session('selected_tahun_id');

        // If no tahun selected in session, default to the latest year present
        // in barang_yang_dilelang.tgl_start_penawaran (if a matching `Tahun` exists).
        if (empty($tahunSelected)) {
            $latestTkd = Tkd::query()
                ->whereNotNull('tgl_start_penawaran')
                ->where('tgl_start_penawaran', '!=', '')
                ->orderByDesc('tgl_start_penawaran')
                ->first();

            if ($latestTkd && $latestTkd->tgl_start_penawaran) {
                $latestYear = date('Y', strtotime($latestTkd->tgl_start_penawaran));
                $tahunRecord = Tahun::where('tahun', $latestYear)->first();
                if ($tahunRecord) {
                    $tahunSelected = $tahunRecord->id;
                    // also persist to session so front-end sees it
                    session(['selected_tahun_id' => $tahunSelected]);
                }
            }
        }

        $tahunSelectedName = Tahun::where('id', $tahunSelected)->value('tahun');
        $lokasiSelected = session('selected_lokasi');
        $totalPendaftar = 0;
        $tahun = Tahun::all();
        $kecamatans = Kecamatan::all();
        $lokasiOptions = Tkd::query()
            ->whereNotNull('lokasi')
            ->where('lokasi', '!=', '')
            ->distinct()
            ->orderBy('lokasi')
            ->pluck('lokasi');

        $lelangQuery = Tkd::query()
            ->select(
                'barang_yang_dilelang.id',
                'barang_yang_dilelang.nama_barang',
                'barang_yang_dilelang.kategori',
                'barang_yang_dilelang.merk',
                'barang_yang_dilelang.harga_dasar',
                'barang_yang_dilelang.foto',
                'barang_yang_dilelang.tgl_start_penawaran',
                'barang_yang_dilelang.tgl_akhir_penawaran',
                'kelurahans.kelurahan'
            )
            ->leftJoin('kelurahans', 'barang_yang_dilelang.id_branch', '=', 'kelurahans.id')
            ->whereNull('barang_yang_dilelang.deleted_at')
            ->latest('barang_yang_dilelang.id');

        if (!empty($lokasiSelected)) {
            $lelangQuery->where('barang_yang_dilelang.lokasi', $lokasiSelected);
        }

        if (!empty($tahunSelectedName)) {
            // filter by the year part of tgl_start_penawaran to avoid mismatches
            $lelangQuery->whereYear('barang_yang_dilelang.tgl_start_penawaran', $tahunSelectedName);
        }

        $lelangItems = $lelangQuery
            ->limit(12)
            ->get()
            ->map(function ($item) {
                $item->harga_dasar_rupiah = 'Rp ' . number_format((int) ($item->harga_dasar ?? 0), 0, ',', '.');
                $item->foto_url = $item->foto ? asset('storage/' . $item->foto) : asset('images/gedung_midi.png');

                if ($item->tgl_start_penawaran && $item->tgl_akhir_penawaran) {
                    $item->status_waktu = date('d M Y', strtotime($item->tgl_start_penawaran)) . ' - ' . date('d M Y', strtotime($item->tgl_akhir_penawaran));
                } elseif ($item->tgl_start_penawaran) {
                    $item->status_waktu = 'Mulai ' . date('d M Y', strtotime($item->tgl_start_penawaran));
                } else {
                    $item->status_waktu = 'Jadwal belum ditentukan';
                }

                return $item;
            });

        $totalPendaftar = 0;
        if (!empty($tahunSelectedName) && !empty($lokasiSelected)) {
            $totalPendaftar = Penawaran::query()
                ->join('barang_yang_dilelang', 'penawarans.idfk_barang', '=', 'barang_yang_dilelang.id')
                ->whereYear('barang_yang_dilelang.tgl_start_penawaran', $tahunSelectedName)
                ->where('barang_yang_dilelang.lokasi', $lokasiSelected)
                ->distinct('penawarans.id_user')
                ->count('penawarans.id_user');
        }

        return view('home')->with([
            'kecamatans' => $kecamatans,
            'tahun' => $tahun,
            'lokasiOptions' => $lokasiOptions,
            'tahunSelected' => $tahunSelected,
            'lokasiSelected' => $lokasiSelected,
            'totalPendaftar' => $totalPendaftar,
            'lelangItems' => $lelangItems,
        ]);
    }

    public function riwayatLelang()
    {
        $user = Auth::user();

        if (!$user || !$this->isUserRole($user)) {
            return redirect()->route('dashboard');
        }

        $totalLelangDiikuti = Penawaran::query()
            ->where('id_user', $user->id)
            ->whereNull('deleted_at')
            ->distinct('idfk_barang')
            ->count('idfk_barang');

        $lelangDimenangkan = Penawaran::query()
            ->where('id_user', $user->id)
            ->where('gugur', 0)
            ->whereNull('deleted_at')
            ->distinct('idfk_barang')
            ->count('idfk_barang');

        $statusAkun = $user->isverified ? 'Terverifikasi' : 'Menunggu Verifikasi';

        $lelangAktif = Tkd::query()
            ->select(
                'barang_yang_dilelang.id',
                'barang_yang_dilelang.nama_barang',
                'barang_yang_dilelang.status',
                'barang_yang_dilelang.kategori',
                'barang_yang_dilelang.merk',
                'barang_yang_dilelang.harga_dasar',
                'barang_yang_dilelang.foto',
                'barang_yang_dilelang.tgl_start_penawaran',
                'barang_yang_dilelang.tgl_akhir_penawaran',
                'barang_yang_dilelang.lokasi'
            )
            ->join('penawarans', 'penawarans.idfk_barang', '=', 'barang_yang_dilelang.id')
            ->where('penawarans.id_user', $user->id)
            ->whereNull('penawarans.deleted_at')
            ->whereNull('barang_yang_dilelang.deleted_at')
            ->distinct()
            ->latest('barang_yang_dilelang.id')
            ->get()
            ->map(function ($item) {
                $item->foto_url = $item->foto
                    ? (file_exists(public_path($item->foto)) ? asset($item->foto) : asset('storage/' . $item->foto))
                    : asset('images/gedung_midi.png');
                $item->harga_dasar_rupiah = 'Rp ' . number_format((int) ($item->harga_dasar ?? 0), 0, ',', '.');
                $item->nama_tampil = $item->nama_barang ?: trim(($item->kategori ?: '-') . ' ' . ($item->merk ?: ''));

                if ($item->tgl_start_penawaran && $item->tgl_akhir_penawaran) {
                    $item->status_waktu = date('d M Y', strtotime($item->tgl_start_penawaran)) . ' - ' . date('d M Y', strtotime($item->tgl_akhir_penawaran));
                } elseif ($item->tgl_start_penawaran) {
                    $item->status_waktu = 'Mulai ' . date('d M Y', strtotime($item->tgl_start_penawaran));
                } else {
                    $item->status_waktu = 'Jadwal belum ditentukan';
                }

                return $item;
            });

        $notifikasi = $this->getDashboardNotifications($user);

        $isRiwayatPage = true;

        return view('dashboard.user', compact(
            'totalLelangDiikuti',
            'lelangDimenangkan',
            'statusAkun',
            'lelangAktif',
            'notifikasi',
            'isRiwayatPage'
        ));
    }

    public function penawaranSaya()
    {
        $user = Auth::user();

        if (!$user || !$this->isUserRole($user)) {
            return redirect()->route('dashboard');
        }

        $totalLelangDiikuti = Penawaran::query()
            ->where('id_user', $user->id)
            ->whereNull('deleted_at')
            ->distinct('idfk_barang')
            ->count('idfk_barang');

        $lelangDimenangkan = Penawaran::query()
            ->where('id_user', $user->id)
            ->where('gugur', 2)
            ->whereNull('deleted_at')
            ->distinct('idfk_barang')
            ->count('idfk_barang');

        $statusAkun = $user->isverified ? 'Terverifikasi' : 'Menunggu Verifikasi';

        $lelangAktif = Tkd::query()
            ->select(
                'barang_yang_dilelang.id',
                'barang_yang_dilelang.nama_barang',
                'barang_yang_dilelang.status',
                'barang_yang_dilelang.kategori',
                'barang_yang_dilelang.merk',
                'barang_yang_dilelang.harga_dasar',
                'barang_yang_dilelang.foto',
                'barang_yang_dilelang.tgl_start_penawaran',
                'barang_yang_dilelang.tgl_akhir_penawaran',
                'penawarans.nilai_penawaran as nilai_penawaran_user',
                'barang_yang_dilelang.lokasi'
            )
            ->join('penawarans', 'penawarans.idfk_barang', '=', 'barang_yang_dilelang.id')
            ->where('penawarans.id_user', $user->id)
            ->where('penawarans.gugur', 2)
            ->whereNull('penawarans.deleted_at')
            ->whereNull('barang_yang_dilelang.deleted_at')
            ->distinct()
            ->latest('barang_yang_dilelang.id')
            ->get()
            ->map(function ($item) {
                $item->foto_url = $item->foto
                    ? (file_exists(public_path($item->foto)) ? asset($item->foto) : asset('storage/' . $item->foto))
                    : asset('images/gedung_midi.png');
                $item->harga_dasar_rupiah = 'Rp ' . number_format((int) ($item->harga_dasar ?? 0), 0, ',', '.');
                $item->penawaran_user_rupiah = 'Rp ' . number_format((int) ($item->nilai_penawaran_user ?? 0), 0, ',', '.');
                $item->nama_tampil = $item->nama_barang ?: trim(($item->kategori ?: '-') . ' ' . ($item->merk ?: ''));

                if ($item->tgl_start_penawaran && $item->tgl_akhir_penawaran) {
                    $item->status_waktu = date('d M Y', strtotime($item->tgl_start_penawaran)) . ' - ' . date('d M Y', strtotime($item->tgl_akhir_penawaran));
                } elseif ($item->tgl_start_penawaran) {
                    $item->status_waktu = 'Mulai ' . date('d M Y', strtotime($item->tgl_start_penawaran));
                } else {
                    $item->status_waktu = 'Jadwal belum ditentukan';
                }

                return $item;
            });

        $notifikasi = $this->getDashboardNotifications($user);

        $isPenawaranSayaPage = true;

        return view('dashboard.penawaran-saya', compact(
            'totalLelangDiikuti',
            'lelangDimenangkan',
            'statusAkun',
            'lelangAktif',
            'notifikasi',
            'isPenawaranSayaPage'
        ));
    }
    public function requestAjaxLogin(Request $request)
    {
        if ($request->ajax() && $request->has('tahun_id')) {
            $userId = Auth::user()->id;
            $user = Auth::user();
            $userKecamatan = Profile::where('id_user', $userId)->value('id_kecamatan');
            $tahunId = $request->input('tahun_id');
            $tahunName = Tahun::select('tahuns.id', 'tahuns.tahun')->where('id', $tahunId)->pluck('tahun')->first();

            $query = Kelurahan::select(
                'kelurahans.id',
                'kelurahans.kelurahan',
                'daerahs.tanggal_lelang',
                'daerahs.id_kecamatan',
                'kecamatans.kecamatan'
            )
                ->leftJoin('daerahs', function ($join) use ($tahunName) {
                    $join->on('kelurahans.id', '=', 'daerahs.id_kelurahan')
                        ->whereYear('daerahs.tanggal_lelang', $tahunName);
                })
                ->leftJoin('kecamatans', 'kelurahans.id_kecamatan', '=', 'kecamatans.id');

            if ($this->isUserRole($user)) {
                $query->where('kelurahans.id_kecamatan', $userKecamatan);
            }

            $data = $query->get();

            return response()->json($data);
        }
    }


    public function storeSelectedValues(Request $request)
    {
        $selectedTahunId = $request->input('tahun_id');
        $selectedLokasi = $request->input('lokasi');
        session(['selected_tahun_id' => $selectedTahunId, 'selected_lokasi' => $selectedLokasi]);

        return response()->json(['message' => 'Selected values stored successfully']);
    }

    public function getTotalPendaftar()
    {
        $selectedTahunId = session('selected_tahun_id');
        $tahunSelected = Tahun::where('id', $selectedTahunId)->value('tahun');
        $selectedLokasi = session('selected_lokasi');
        $totalPendaftar = 0;
        if (!empty($tahunSelected)) {
            $query = Penawaran::query()
                ->join('barang_yang_dilelang', 'penawarans.idfk_barang', '=', 'barang_yang_dilelang.id')
                ->whereYear('barang_yang_dilelang.tgl_start_penawaran', $tahunSelected);

            if (!empty($selectedLokasi)) {
                $query->where('barang_yang_dilelang.lokasi', $selectedLokasi);
            }

            $totalPendaftar = $query->distinct('penawarans.id_user')->count('penawarans.id_user');
        }
        return response()->json(['totalPendaftar' => $totalPendaftar]);
    }

    public function getTotalPenawaran()
    {
        $selectedTahunId = session('selected_tahun_id');
        $tahunSelected = Tahun::where('id', $selectedTahunId)->value('tahun');
        $selectedLokasi = session('selected_lokasi');
        $totalPenawaran = 0;
        if (!empty($tahunSelected)) {
            $query = Penawaran::query()
                ->join('barang_yang_dilelang', 'penawarans.idfk_barang', '=', 'barang_yang_dilelang.id')
                ->whereYear('barang_yang_dilelang.tgl_start_penawaran', $tahunSelected);

            if (!empty($selectedLokasi)) {
                $query->where('barang_yang_dilelang.lokasi', $selectedLokasi);
            }

            $totalPenawaran = $query->count();
        }
        return response()->json(['totalPenawaran' => $totalPenawaran]);
    }

    public function getTotalDaerah()
    {
        $selectedTahunId = session('selected_tahun_id');
        $tahunSelected = Tahun::where('id', $selectedTahunId)->value('tahun');
        $selectedLokasi = session('selected_lokasi');
        $totalDaerah = 0;
        if (!empty($tahunSelected)) {
            $query = Tkd::query()->whereYear('barang_yang_dilelang.tgl_start_penawaran', $tahunSelected);

            if (!empty($selectedLokasi)) {
                $query->where('barang_yang_dilelang.lokasi', $selectedLokasi);
            }

            $totalDaerah = $query->count();
        }
        return response()->json(['totalDaerah' => $totalDaerah]);
    }

    public function getTotalTkd()
    {
        $selectedTahunId = session('selected_tahun_id');
        $tahunSelected = Tahun::where('id', $selectedTahunId)->value('tahun');
        $selectedLokasi = session('selected_lokasi');
        $totalTkd = 0;
        if (!empty($tahunSelected)) {
            $query = Tkd::query()->whereYear('barang_yang_dilelang.tgl_start_penawaran', $tahunSelected);

            if (!empty($selectedLokasi)) {
                $query->where('barang_yang_dilelang.lokasi', $selectedLokasi);
            }

            $totalTkd = $query->count();
        }
        return response()->json(['totalTkd' => $totalTkd]);
    }

    public function showLelang(Tkd $tkd)
    {
        $submittedPenawaranCount = 0;
        $isCurrentUserWinner = false;
        $currentUser = Auth::user();

        if ($currentUser) {
            $submittedPenawaranCount = Penawaran::query()
                ->where('id_user', $currentUser->id)
                ->where('idfk_barang', $tkd->id)
                ->count();
        }

        $detail = Tkd::query()
            ->select(
                'barang_yang_dilelang.id',
                'barang_yang_dilelang.aset_id',
                'barang_yang_dilelang.id_branch',
                'barang_yang_dilelang.nama_barang',
                'barang_yang_dilelang.kondisi',
                'barang_yang_dilelang.kategori',
                'barang_yang_dilelang.merk',
                'barang_yang_dilelang.lokasi',
                'barang_yang_dilelang.harga_dasar',
                'barang_yang_dilelang.kelipatan',
                'barang_yang_dilelang.tahun',
                'barang_yang_dilelang.status',
                'barang_yang_dilelang.keterangan',
                'barang_yang_dilelang.foto',
                'barang_yang_dilelang.tgl_start_penawaran',
                'barang_yang_dilelang.tgl_akhir_penawaran'
            )
            ->where('barang_yang_dilelang.id', $tkd->id)
            ->whereNull('barang_yang_dilelang.deleted_at')
            ->firstOrFail();

        $detail->nama_tampil = $detail->nama_barang ?: trim(($detail->kategori ?: '-') . ' ' . ($detail->merk ?: ''));
        $detail->foto_url = $detail->foto ? asset('storage/' . $detail->foto) : asset('images/gedung_midi.png');
        $detail->harga_dasar_rupiah = 'Rp ' . number_format((int) ($detail->harga_dasar ?? 0), 0, ',', '.');
        $detail->tgl_start_penawaran_label = $detail->tgl_start_penawaran
            ? date('d M Y H:i', strtotime($detail->tgl_start_penawaran))
            : 'Jadwal belum ditentukan';
        $detail->tgl_akhir_penawaran_label = $detail->tgl_akhir_penawaran
            ? date('d M Y H:i', strtotime($detail->tgl_akhir_penawaran))
            : 'Jadwal belum ditentukan';
        $detail->tgl_akhir_penawaran_iso = $detail->tgl_akhir_penawaran
            ? date('c', strtotime($detail->tgl_akhir_penawaran))
            : null;
        $detail->status_waktu = $detail->tgl_start_penawaran && $detail->tgl_akhir_penawaran
            ? date('d M Y H:i', strtotime($detail->tgl_start_penawaran)) . ' - ' . date('d M Y H:i', strtotime($detail->tgl_akhir_penawaran))
            : ($detail->tgl_start_penawaran
                ? 'Mulai ' . date('d M Y H:i', strtotime($detail->tgl_start_penawaran))
                : 'Jadwal belum ditentukan');

        $this->lelangSettlementService->settleExpiredByBarangId((int) $tkd->id);

        if ($currentUser) {
            $isCurrentUserWinner = Penawaran::query()
                ->where('id_user', $currentUser->id)
                ->where('idfk_barang', $tkd->id)
                ->where('gugur', 2)
                ->exists();
        }

        $riwayatPenawaran = Penawaran::query()
            ->select('name', 'nilai_penawaran', 'gugur', 'created_at')
            ->where('idfk_barang', $tkd->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($item) {
                $item->nama_tersensor = $this->maskNamaPenawar($item->name);
                $gugurStatus = (int) $item->gugur;
                $item->status_label = $gugurStatus === 2
                    ? 'Pemenang Lelang'
                    : ($gugurStatus === 1 ? 'Gugur' : 'Ditinjau');
                $item->tanggal_penawaran_label = $item->created_at
                    ? date('d M Y H:i', strtotime($item->created_at))
                    : '-';
                $item->nilai_penawaran_rupiah = 'Rp ' . number_format((int) ($item->nilai_penawaran ?? 0), 0, ',', '.');

                return $item;
            });

        return view('dashboard.lelang-detail', compact('detail', 'submittedPenawaranCount', 'riwayatPenawaran', 'isCurrentUserWinner'));
    }

    private function maskNamaPenawar(?string $nama): string
    {
        $nama = trim((string) $nama);

        if ($nama === '') {
            return '-';
        }

        $chars = preg_split('//u', $nama, -1, PREG_SPLIT_NO_EMPTY);
        $charCount = is_array($chars) ? count($chars) : 0;

        if ($charCount <= 2) {
            return $nama;
        }

        $first = $chars[0];
        $last = $chars[$charCount - 1];

        return $first . str_repeat('*', $charCount - 2) . $last;
    }

    public function getKelurahansDashboard()
    {
        $selectedTahunId = session('selected_tahun_id');
        $tahunSelected = Tahun::select('id', 'tahun')->where('id', $selectedTahunId)->first();
        $selectedLokasi = session('selected_lokasi');
        $lokasi = $selectedLokasi ? Tkd::query()->where('lokasi', $selectedLokasi)->first() : null;
        return response()->json([
            'lokasi' => $lokasi,
            'tahunSelected' => $tahunSelected
        ]);
    }

    public function infoBlacklist()
    {
        $blacklistedUsers = User::query()
            ->where('is_blacklisted', true)
            ->whereNull('deleted_at')
            ->select('id', 'name', 'username', 'email', 'is_blacklisted', 'keterangan')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($user) {
                $user->username_tersensor = $this->censorUsername($user->username);
                return $user;
            });

        return view('dashboard.info-blacklist', compact('blacklistedUsers'));
    }

    private function getDashboardNotifications(?User $user)
    {
        if (!$user) {
            return collect();
        }

        $roleName = strtolower((string) ($user->role_name ?? ''));

        return DB::table('notification')
            ->where('role', $roleName)
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
    }
}
