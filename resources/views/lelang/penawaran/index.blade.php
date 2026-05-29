@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Penawaran List</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Table</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Penawaran Management</h2>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Penawaran List</h4>
                            <div class="card-header-action">
                                <a class="btn btn-icon icon-left btn-success" href="{{ route('penawaran.export') }}"
                                    data-id="export">
                                    <i class="fa fa-file-excel" aria-hidden="true"></i>
                                    Export Excel</a>

                                <a class="btn btn-danger btn-danger active cetak bg-danger">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                    Cetak Data</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="show-create mb-3" style="display: none">
                                <form id="create" method="POST" action="{{ route('penawaran.handleForm') }}">
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="penawaran">Bukti Hak (SHP)</label>
                                            <select
                                                class="form-control select2
                                                    @error('penawaran') is-invalid @enderror"
                                                name="penawaran">
                                                <option value="">Pilih Bukti Hak (SHP)</option>
                                                @foreach ($daftarList as $daftarListNoUrut)
                                                    <option value="{{ $daftarListNoUrut->id }}">
                                                        {{ $daftarListNoUrut->no_urut }} - {{ $daftarListNoUrut->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('penawaran')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="text-right" style="padding-top: 29px">
                                            <button class="btn btn-primary mr-1" type="submit"
                                                style="height: 41px">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="show-import-ba mb-4" style="display: none">
                                @error('import-file')
                                    <div class="invalid-feedback d-flex mb-10" role="alert">
                                        <div class="alert_alert-dange_mt-1_mb-1">
                                            {{ $message }}
                                        </div>
                                    </div>
                                @enderror
                                <div class="custom-file">
                                    <form action="{{ route('daerahs.upload') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <label
                                            class="custom-file-label @error('import-file', 'ImportBaRequest') is-invalid @enderror"
                                            for="file-upload1">Choose File</label>
                                        <input type="file" id="file-upload1" class="custom-file-input" name="import-file"
                                            data-id="send-import">
                                        <br /><br />
                                        <div class="footer text-right">
                                            <button class="btn btn-primary" data-id="submit-import">Upload File</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="show-import-shp mb-4" style="display: none">
                                @error('import-file')
                                    <div class="invalid-feedback d-flex mb-10" role="alert">
                                        <div class="alert_alert-dange_mt-1_mb-1">
                                            {{ $message }}
                                        </div>
                                    </div>
                                @enderror
                                <div class="custom-file">
                                    <form action="{{ route('daerahs.upload.shp') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        <label
                                            class="custom-file-label @error('import-file', 'ImportPenawaranRequest') is-invalid @enderror"
                                            for="file-upload2">Choose File</label>
                                        <input type="file" id="file-upload2" class="custom-file-input"
                                            name="import-file" data-id="send-import">
                                        <br /><br />
                                        <div class="footer text-right">
                                            <button class="btn btn-primary" data-id="submit-import">Upload File</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="show-cetak mb-3" style="display: none">
                                <a class="btn btn-info btn-success active bg-success" href="{{ route('hektar') }}">
                                    <i class="fas fa-check"></i>
                                    Luas Lebih dari 2 Hektar</a>
                                <a class="btn btn-info btn-danger active bg-danger" target="_blank"
                                    href="{{ route('penawaran.cetaktidaklaku') }}">
                                    <i class="fas fa-times"></i>
                                    Bidang/SHP Tidak Laku</a>

                                <a class="btn btn-info btn-primary active bg-primary" target="_blank"
                                    href="{{ route('penawaran.cetakba') }}">
                                    <i class="far fa-file"></i>
                                    Lampiran BA</a>
                                <a class="btn btn-info btn-primary active bg-primary" target="_blank"
                                    href="{{ route('penawaran.cetaksekota') }}">
                                    <i class="far fa-file"></i>
                                    Rekap Se-kota</a>
                            </div>
                            <div class="show-search mb-3" style="display: none">
                                <form id="search" method="GET" action="{{ route('penawaran.index') }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="role">Bukti Hak (SHP)</label>
                                            <select class="form-control select2" name="tkdsearch">
                                                <option value="">Pilih Bukti Hak (SHP)</option>
                                                @foreach ($tkdDropdown as $daftarListNoUrut)
                                                    <option value="{{ $daftarListNoUrut->id }}">
                                                        {{ $daftarListNoUrut->lokasi }} bidang
                                                        {{ $daftarListNoUrut->kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="role">User</label>
                                            <input type="text" name="nama" class="form-control" id="nama"
                                                placeholder="Nama Pendaftar" value="{{ $nama }}">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-primary mr-1" type="submit">Submit</button>
                                        <a class="btn btn-secondary" href="{{ route('penawaran.index') }}">Reset</a>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tbody>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Nama Barang</th>
                                            <th>Merk</th>
                                            <th>Lokasi</th>
                                            <th>Harga Dasar</th>
                                            <th>Kelipatan</th>
                                            <th>Nilai Penawaran</th>
                                            <th>Status Nilai</th>
                                            <th>Tgl Akhir Penawaran</th>
                                            <th class="text-right" style="width: 230px">Action</th>
                                        </tr>
                                        @foreach ($penawarans as $key => $penawaran)
                                            <tr>
                                                <td>{{ ($penawarans->currentPage() - 1) * $penawarans->perPage() + $key + 1 }}
                                                </td>
                                                <td>{{ $penawaran->name ?? '-' }}</td>
                                                <td>{{ $penawaran->nama_barang ?? '-' }}</td>
                                                <td>{{ $penawaran->merk ?? '-' }}</td>
                                                <td>{{ $penawaran->lokasi ?? '-' }}</td>
                                                <td>Rp {{ number_format((int) ($penawaran->harga_dasar ?? 0), 0, ',', '.') }}</td>
                                                <td>{{ $penawaran->kelipatan ?? '-' }}</td>
                                                <td>Rp {{ number_format((int) ($penawaran->nilai_penawaran ?? 0), 0, ',', '.') }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-end">
                                                        <a href="#" data-toggle="dropdown"
                                                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                                            {{ optional($statusPelelangs->firstWhere('id', $penawaran->gugur))->status_pelelang ?? '-' }}
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            @foreach ($statusPelelangs as $statusPelelang)
                                                                <form method="POST"
                                                                    action="{{ route('penawaran.update-status-pelelang', $penawaran->id) }}">
                                                                    @csrf
                                                                    <input type="hidden" name="gugur"
                                                                        value="{{ $statusPelelang->id }}">
                                                                    <button type="submit"
                                                                        class="dropdown-item has-icon">
                                                                        {{ $statusPelelang->status_pelelang }}
                                                                    </button>
                                                                </form>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $penawaran->tgl_akhir_penawaran ? date('d-m-Y H:i', strtotime($penawaran->tgl_akhir_penawaran)) : '-' }}</td>
                                                <td class="text-right">
                                                    <div class="d-flex justify-content-end">
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="#" class="dropdown-item has-icon">
                                                                Pemenang II
                                                            </a>
                                                            <div class="dropdown-divider"></div>
                                                            @if ($penawaran->gugur == false)
                                                                <a href="{{ route('penawaran.toggle-gugur', $penawaran->id) }}"
                                                                    class="dropdown-item has-icon text-danger">
                                                                    Digugurkan
                                                                </a>
                                                            @else
                                                                <a href="{{ route('penawaran.toggle-gugur', $penawaran->id) }}"
                                                                    class="dropdown-item has-icon text-danger">
                                                                    Kembali Ke Panitia
                                                                </a>
                                                            @endif

                                                        </div>
                                                        <a href="{{ route('penawaran.edit', $penawaran->id) }}"
                                                            class="btn btn-sm btn-info btn-icon "
                                                            style="padding-top: 7px"><i class="fas fa-edit"></i>Edit</a>
                                                        <form action="{{ route('penawaran.destroy', $penawaran->id) }}"
                                                            method="POST" class="ml-2">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token"
                                                                value="{{ csrf_token() }}">
                                                            <button class="btn btn-sm btn-danger btn-icon confirm-delete"
                                                                type="submit" style="height: 40px">
                                                                <i class="fas fa-times"></i> Delete </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {{ $penawarans->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="importModal" tabindex="-1" role="dialog"
                            aria-labelledby="importModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="importModalLabel">Import Excel Penawaran</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-info mb-3">
                                            Download template dulu, isi sesuai kolom di bawah, lalu upload file Excel yang sudah diisi.
                                        </div>
                                        <div class="table-responsive mb-3">
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Id Penawaran</th>
                                                        <th>Id User</th>
                                                        <th>Name</th>
                                                        <th>Id FK Barang</th>
                                                        <th>Aset ID</th>
                                                        <th>Nilai Penawaran</th>
                                                        <th>Gugur</th>
                                                        <th>Status Pelelang ID</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1X12</td>
                                                        <td>12</td>
                                                        <td>Nama User</td>
                                                        <td>8</td>
                                                        <td>AS001</td>
                                                        <td>5000000</td>
                                                        <td>0</td>
                                                        <td>1</td>
                                                        <td>Penawaran awal</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mb-3">
                                            <a href="{{ route('penawaran.template') }}" class="btn btn-outline-primary">
                                                <i class="fa fa-download"></i> Download Template Excel
                                            </a>
                                        </div>
                                        <form action="{{ route('penawaran.import') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="import_file">Upload File Excel</label>
                                                <input type="file" id="import_file"
                                                    class="form-control @error('import-file') is-invalid @enderror"
                                                    name="import-file" accept=".xlsx,.xls,.csv,.ods" required>
                                                @error('import-file')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="text-right">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning">Import File</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('customScript')
    <script src="/assets/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#importModal').appendTo('body');

            $('.search').click(function(event) {
                event.stopPropagation();
                $(".show-search").slideToggle("fast");
                $(".show-create").hide();
                $(".show-cetak").hide();
                $(".show-import-shp").hide();
                $(".show-import-ba").hide();
            });
            $('.create').click(function(event) {
                event.stopPropagation();
                $(".show-create").slideToggle("fast");
                $(".show-cetak").hide();
                $(".show-import-shp").hide();
                $(".show-import-ba").hide();
            });
            $('.cetak').click(function(event) {
                event.stopPropagation();
                $(".show-cetak").slideToggle("fast");
                $(".show-create").hide();
                $(".show-import-shp").hide();
                $(".show-import-ba").hide();
            });

            $('.import-shp').click(function(event) {
                event.stopPropagation();
                $(".show-import-shp").slideToggle("fast");
                $(".show-create").hide();
                $(".show-cetak").hide();

            });
            $('.import-ba').click(function(event) {
                event.stopPropagation();
                $(".show-import-ba").slideToggle("fast");
                $(".show-create").hide();
                $(".show-cetak").hide();
                $(".show-import-shp").hide();
            });

            $('#importModal').on('show.bs.modal', function() {
                $('.navbar .form-inline .search-backdrop').css({
                    opacity: 0,
                    visibility: 'hidden'
                });
            });

            $('#file-upload1').change(function() {
                var i = $(this).prev('label').clone();
                var file = $('#file-upload1')[0].files[0].name;
                $(this).prev('label').text(file);
            });
            $('#file-upload2').change(function() {
                var i = $(this).prev('label').clone();
                var file = $('#file-upload2')[0].files[0].name;
                $(this).prev('label').text(file);
            });
        });
    </script>
@endpush

@push('customStyle')
    <link rel="stylesheet" href="/assets/css/select2.min.css">
    <style>
        #importModal {
            z-index: 10055;
        }

        .modal-backdrop {
            z-index: 10040;
        }
    </style>
@endpush

