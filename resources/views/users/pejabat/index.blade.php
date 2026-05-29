@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Pejabat List</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Table</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Karyawan Management</h2>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Karyawan List</h4>
                            <div class="card-header-action">
                                <a class="btn btn-icon icon-left btn-primary" href="{{ route('pejabat.create') }}">
                                    <i class="far fa-file"></i>
                                    Create Karyawan</a>
                                <a class="btn btn-icon icon-left btn-success" href="{{ route('pejabat.export') }}">
                                    <i class="fa fa-file-excel"></i> Export Excel
                                </a>
                                <button type="button" class="btn btn-icon icon-left btn-warning" data-toggle="modal"
                                    data-target="#importModal">
                                    <i class="fa fa-file-upload"></i> Import Excel
                                </button>
                                <a class="btn btn-info btn-info active search bg-info">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                    Search Karyawan</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="show-search mb-3" style="display: none">
                                <form id="search" method="GET" action="{{ route('pejabat.index') }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="role">Karyawan</label>
                                            <input type="text" name="pejabat" class="form-control" id="pejabat"
                                                placeholder="Nama Karyawan" value="{{ $pejabatSearch }}">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-primary mr-1" type="submit">Submit</button>
                                        <a class="btn btn-secondary" href="{{ route('pejabat.index') }}">Reset</a>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tbody>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Branch</th>
                                                <th>NIK</th>
                                            <th>Gender</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                        @foreach ($pejabats as $key => $pejabat)
                                            <tr>
                                                <td>{{ ($pejabats->currentPage() - 1) * $pejabats->perPage() + $key + 1 }}
                                                </td>
                                                <td>{{ $pejabat->nama_karyawan }}</td>
                                                <td>{{ $pejabat->jabatan->jabatan ?? '-' }}</td>
                                                <td>{{ $pejabat->branch->branch ?? '-' }}</td>
                                                <td>{{ $pejabat->nik }}</td>
                                                <td>{{ $pejabat->gender }}</td>
                                                <td class="text-right">
                                                    <div class="d-flex justify-content-end">
                                                        <a href="{{ route('pejabat.edit', $pejabat->id) }}"
                                                            class="btn btn-sm btn-info btn-icon "><i
                                                                class="fas fa-edit"></i>
                                                            Edit</a>
                                                        <form action="{{ route('pejabat.destroy', $pejabat->id) }}"
                                                            method="POST" class="ml-2">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token"
                                                                value="{{ csrf_token() }}">
                                                            <button class="btn btn-sm btn-danger btn-icon confirm-delete">
                                                                <i class="fas fa-times"></i> Delete </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {{ $pejabats->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="importModal" tabindex="-1" role="dialog"
                            aria-labelledby="importModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="importModalLabel">Import Excel Karyawan</h5>
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
                                                        <th>Jabatan</th>
                                                        <th>Branch</th>
                                                        <th>Nama Karyawan</th>
                                                        <th>NIK</th>
                                                        <th>Gender</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Manager</td>
                                                        <td>Head Office</td>
                                                        <td>Nama Karyawan</td>
                                                        <td>1234567890</td>
                                                        <td>Male</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mb-3">
                                            <a href="{{ route('pejabat.template') }}" class="btn btn-outline-primary">
                                                <i class="fa fa-download"></i> Download Template Excel
                                            </a>
                                        </div>
                                        <form action="{{ route('pejabat.import') }}" method="POST"
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
    <script>
        $(document).ready(function() {
            $('#importModal').appendTo('body');

            $('.search').click(function(event) {
                event.stopPropagation();
                $(".show-search").slideToggle("fast");
            });

            $('#importModal').on('show.bs.modal', function() {
                $('.navbar .form-inline .search-backdrop').css({
                    opacity: 0,
                    visibility: 'hidden'
                });
            });
        });
    </script>
@endpush

@push('customStyle')
    <style>
        #importModal {
            z-index: 10055;
        }

        .modal-backdrop {
            z-index: 10040;
        }
    </style>
@endpush
