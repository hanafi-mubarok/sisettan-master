<?php

namespace App\Http\Controllers;

use App\Exports\TkdsExport;
use App\Http\Requests\ImportTkdRequest;
use App\Models\Tkd;
use App\Http\Requests\StoreTkdRequest;
use App\Http\Requests\UpdateTkdRequest;
use App\Imports\TkdsImport;
use App\Models\Daerah;
use App\Models\Daftar;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Kelurahan;
use App\Models\Notification as NotificationModel;
use App\Models\StatusBarang;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class TkdController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:tkd.index')->only('index');
        $this->middleware('permission:tkd.create')->only('create', 'store');
        $this->middleware('permission:tkd.edit')->only('edit', 'update', 'approve');
        $this->middleware('permission:tkd.destroy')->only('destroy');
    }

    public function index(Request $request)
    {
        $branches = Kelurahan::all();
        $tkdName = $request->input('tkd');
        $branchIds = $request->input('branch');
        $tkd = $request->input('tkd');

        $query = Tkd::where('barang_yang_dilelang.deleted_at', null)
            ->when($request->input('lokasi'), function ($query, $lokasi) {
                return $query->where('barang_yang_dilelang.lokasi', 'like', '%' . $lokasi . '%');
            })
            ->when($request->input('branch'), function ($query, $branch) {
                return $query->whereIn('barang_yang_dilelang.id_branch', $branch);
            })
            ->select(
                'barang_yang_dilelang.id',
                'barang_yang_dilelang.aset_id',
                'barang_yang_dilelang.nama_barang',
                'barang_yang_dilelang.kondisi',
                'barang_yang_dilelang.merk',
                'barang_yang_dilelang.kategori',
                'barang_yang_dilelang.lokasi',
                'barang_yang_dilelang.kelipatan',
                'barang_yang_dilelang.harga_dasar',
                'barang_yang_dilelang.tahun',
                'barang_yang_dilelang.status',
                'barang_yang_dilelang.tgl_akhir_penawaran',
                'barang_yang_dilelang.keterangan',
                'barang_yang_dilelang.foto'
            )
            ->paginate(20);
        $branchSelected = $request->input('branch');

        $query->appends(['tkd' => $tkdName, 'branch' => $branchIds]);

        return view('lelang.tkd.index')->with([
            'tkds' => $query,
            'branches' => $branches,
            'tkdName' => $tkdName,
            'branchIds' => $branchIds,
            'branchSelected' => $branchSelected,
            'tkd' => $tkd,
        ]);
    }

    public function create()
    {
        $branches = Kelurahan::all();
        $branchIdFromDaerah = session('selected_kelurahan_id') ?? null;
        $statusOptions = StatusBarang::query()->orderBy('status_name')->get();

        return view('lelang.tkd.create')->with([
            'branches' => $branches,
            'branchIdFromDaerah' => $branchIdFromDaerah,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function store(StoreTkdRequest $request)
    {
        $id_branch = $request->id_branch;
        $count = Tkd::where('id_branch', $id_branch)->count();
        $id_tkd = $id_branch . "S" . ($count + 1);

        // Handle the photo upload
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $fotoPath = $foto->storeAs('fotoTkd', $fotoName, 'public');
        }

        Tkd::create([
            'id_tkd' => $id_tkd,
            'id_branch' => $id_branch,
            'bidang' => $request->bidang,
            'letak' => $request->letak,
            'bukti' => $request->bukti,
            'harga_dasar' => $request->harga_dasar,
            'luas' => $request->luas,
            'keterangan' => $request->keterangan,
            'nop' => $request->nop,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'foto' => $fotoPath ?? null,
            'uploaded_by' => optional(auth()->user())->username,
        ]);

        return redirect()->route('tkd.index')->with('success', 'Tambah Data TKD Sukses');
    }

    public function show(Tkd $tkd)
    {
        return view('lelang.tkd.show', compact('tkd'));
    }

    public function edit(Tkd $tkd)
    {
        $branches = Kelurahan::all();
        $statusOptions = StatusBarang::query()->orderBy('status_name')->get();

        return view('lelang.tkd.edit', compact('tkd'))->with([
            'branches' => $branches,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function update(UpdateTkdRequest $request, Tkd $tkd)
    {
        $validatedData = $request->validated();
        
        // Convert datetime-local format to database format
        if (!empty($validatedData['tgl_start_penawaran'])) {
            $validatedData['tgl_start_penawaran'] = str_replace('T', ' ', $validatedData['tgl_start_penawaran']) . ':00';
        }
        if (!empty($validatedData['tgl_akhir_penawaran'])) {
            $validatedData['tgl_akhir_penawaran'] = str_replace('T', ' ', $validatedData['tgl_akhir_penawaran']) . ':00';
        }
        
        // Prevent non-admin users from changing harga_dasar
        $roleName = strtolower((string) (optional(auth()->user())->role_name ?? ''));
        if (!in_array($roleName, ['super-admin', 'admin_ho'])) {
            // restore original harga_dasar to ignore any tampering
            $validatedData['harga_dasar'] = $tkd->harga_dasar;
        }

        // dd($request->hasFile('foto'));
        if ($request->hasFile('foto')) {
            if ($tkd->foto) {
                Storage::disk('public')->delete($tkd->foto);
            }
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $fotoPath = $foto->storeAs('fotoTkd', $fotoName, 'public');
            $validatedData['foto'] = $fotoPath;
        } else {
            $validatedData['foto'] = $tkd->foto;
        }

        $tkd->update($validatedData);

        return redirect()->route('tkd.index')->with('success', 'Tkd updated successfully.');
    }

    public function approve(Tkd $tkd)
    {
        $roleName = strtolower((string) (optional(auth()->user())->role_name ?? ''));

        abort_unless(in_array($roleName, ['super-admin', 'admin_ho']), 403);

        $waitingApprovalStatus = $this->getStatusBarangValue('WAITING APPROVAL', 'WAITING APPROVAL');
        $approvedStatus = $this->getStatusBarangValue('OPEN', 'OPEN');

        if ((string) $tkd->status !== (string) $waitingApprovalStatus) {
            return redirect()->route('tkd.edit', $tkd)->with('error', 'Status barang hanya bisa di-approve dari WAITING APPROVAL.');
        }

        $tkd->update([
            'status' => $approvedStatus,
        ]);

        return redirect()->route('tkd.edit', $tkd)->with('success', 'Status barang berhasil di-approve dan diubah menjadi OPEN.');
    }

    public function destroy(Tkd $tkd)
    {
        $tkd->delete();
        return redirect()->route('tkd.index')->with('success', 'Tkd deleted successfully.');
    }

    public function import(ImportTkdRequest $request)
    {
        Excel::import(new TkdsImport, $request->file('import-file')->store('import-files'));
        return redirect()->route('tkd.index')->with('success', 'Tambahkan Data TKD Sukses diimport');
    }

    public function export()
    {
        return Excel::download(new TkdsExport, 'Daftar Aset Lelang.xlsx');
    }

    private function normalizeMoneyInput(?string $value): ?int
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $value);

        return $digits === '' ? null : (int) $digits;
    }

    private function getStatusBarangValue(string $statusName, string $fallback): string
    {
        return StatusBarang::query()
            ->where('status_name', $statusName)
            ->value('status_name') ?? $fallback;
    }

    public function downloadTemplate()
    {
        $templatePath = public_path('Excel/templates/tkd_template.xlsx');
        if (!file_exists($templatePath)) {
            return redirect()->route('tkd.index')->with('error', 'Template file not found.');
        }

        return response()->download($templatePath, 'tkd_template.xlsx');
    }

    public function uploadForm()
    {
        $branches = Branch::all();
        $categories = Category::all();
        $tahuns = Tahun::all();
        $statusMenungguApproval = $this->getStatusBarangValue('WAITING APPROVAL', 'WAITING APPROVAL');

        return view('lelang.tkd.upload', compact('branches', 'categories', 'tahuns', 'statusMenungguApproval'));
    }

    public function storeUpload(Request $request)
    {
        \Log::info('Upload form received', $request->all());
        
        $request->merge([
            'harga_dasar' => $this->normalizeMoneyInput($request->input('harga_dasar')),
            'kelipatan' => $this->normalizeMoneyInput($request->input('kelipatan')),
        ]);

        \Log::info('After normalization', $request->all());

        $validated = $request->validate([
            'aset_id' => 'nullable|string',
            'id_branch' => 'required|exists:branch,id',
            'nama_barang' => 'nullable|string',
            'kondisi' => 'nullable|string',
            'kategori' => 'required|string',
            'merk' => 'required|string',
            'lokasi' => 'required|string',
            'harga_dasar' => 'nullable|numeric',
            'kelipatan' => 'nullable|integer',
            'tahun' => 'nullable|integer',
            'status' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:10000',
            'tgl_start_penawaran' => 'nullable|date',
            'tgl_akhir_penawaran' => 'nullable|date',
        ]);

        \Log::info('Validation passed', $validated);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $fotoPath = $foto->storeAs('fotoTkd', $fotoName, 'public');
        }

        $barangBaru = Tkd::create([
            'aset_id' => $validated['aset_id'],
            'id_branch' => $validated['id_branch'],
            'nama_barang' => $validated['nama_barang'],
            'kondisi' => $validated['kondisi'],
            'kategori' => $validated['kategori'],
            'merk' => $validated['merk'],
            'lokasi' => $validated['lokasi'],
            'harga_dasar' => $validated['harga_dasar'],
            'kelipatan' => $validated['kelipatan'],
            'tahun' => $validated['tahun'],
            'status' => $this->getStatusBarangValue('WAITING APPROVAL', 'WAITING APPROVAL'),
            'keterangan' => $validated['keterangan'],
            'foto' => $fotoPath,
            'tgl_start_penawaran' => $validated['tgl_start_penawaran'],
            'tgl_akhir_penawaran' => $validated['tgl_akhir_penawaran'],
            'uploaded_by' => optional(auth()->user())->username,
        ]);

        if ($barangBaru) {
            $pengupload = optional(auth()->user())->name ?? 'User';
            $namaBarang = $barangBaru->nama_barang ?: '-';
            $hargaDasar = $barangBaru->harga_dasar !== null ? $barangBaru->harga_dasar : 0;

            NotificationModel::create([
                'judul' => 'Ada Barang Lelang Baru',
                'detail' => $pengupload . ' mengupload ' . $namaBarang . ' dengan harga ' . $hargaDasar . ' menunggu persetujuan anda.',
                'link' => url('/lelang/tkd/' . $barangBaru->id . '/edit'),
                'role' => 'admin_ho',
                'user_id' => null,
                'is_read' => false,
            ]);
        }

        \Log::info('Record created successfully');

        return redirect()->route('tkd.index')->with('success', 'Barang lelang berhasil ditambahkan');
    }
}

