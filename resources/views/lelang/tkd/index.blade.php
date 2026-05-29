@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Daftar Aset Lelang</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Table</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Aset Lelang Management</h2>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Aset Lelang List</h4>
                            <div class="card-header-action">
                                <a class="btn btn-icon icon-left btn-primary" href="{{ route('tkd.create') }}">
                                    <i class="far fa-file"></i>
                                    Create Aset Lelang</a>
                                <a class="btn btn-info btn-dark active bg-dark" href="{{ route('tkd.export') }}" data-id="export">
                                    <i class="fa fa-upload" aria-hidden="true"></i>
                                    Export Aset Lelang</a>
                                <a class="btn btn-info btn-info active search bg-info">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                    Search Aset</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="show-import mb-4" style="display: none">
                                @error('import-file')
                                    <div class="invalid-feedback d-flex mb-10" role="alert">
                                        <div class="alert_alert-dange_mt-1_mb-1">{{ $message }}</div>
                                    </div>
                                @enderror
                                <div class="custom-file">
                                    <form action="{{ route('tkd.import') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        <label class="custom-file-label @error('import-file', 'ImportTkdRequest') is-invalid @enderror"
                                            for="file-upload">Choose File</label>
                                        <input type="file" id="file-upload" class="custom-file-input" name="import-file"
                                            data-id="send-import">
                                        <br /><br />
                                        <a href="{{ route('tkd.download-template') }}" class="text">Unduh Template</a>
                                        <br /> <br />
                                        <div class="footer text-right">
                                            <button class="btn btn-primary" data-id="submit-import">Import File</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="show-search mb-3" style="display: none">
                                <form id="search" method="GET" action="{{ route('tkd.index') }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="lokasi">Lokasi</label>
                                            <input type="text" name="lokasi" class="form-control" id="lokasi" placeholder="Cari Lokasi">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-primary mr-1" type="submit">Cari</button>
                                        <a class="btn btn-secondary" href="{{ route('tkd.index') }}">Reset</a>
                                    </div>
                                </form>
                            </div>
                            <div class="asset-grid">
                                @foreach ($tkds as $key => $tkd)
                                    @php
                                        $fotoUrl = $tkd->foto ? asset('storage/' . $tkd->foto) : asset('images/gedung_midi.png');
                                    @endphp
                                    <div class="asset-card card border-0 shadow-sm h-100">
                                        <div class="asset-card-image-wrap">
                                            <img src="{{ $fotoUrl }}" alt="Foto {{ $tkd->nama_barang }}"
                                                class="asset-card-image">
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h5 class="mb-1 asset-title">{{ $tkd->nama_barang }}</h5>
                                                    <div class="text-muted small">Aset ID: {{ $tkd->aset_id ?? '-' }}</div>
                                                </div>
                                                <span class="badge badge-light">{{ $tkd->status ?? '-' }}</span>
                                            </div>

                                            <div class="asset-meta">
                                                <div><span>Kondisi</span><strong>{{ $tkd->kondisi ?? '-' }}</strong></div>
                                                <div><span>Merk</span><strong>{{ $tkd->merk ?? '-' }}</strong></div>
                                                <div><span>Kategori</span><strong>{{ $tkd->kategori ?? '-' }}</strong></div>
                                                <div><span>Lokasi</span><strong>{{ $tkd->lokasi ?? '-' }}</strong></div>
                                                <div><span>Kelipatan</span><strong>{{ $tkd->kelipatan ?? '-' }}</strong></div>
                                                <div><span>Harga Dasar</span><strong>Rp {{ number_format((int) ($tkd->harga_dasar ?? 0), 0, ',', '.') }}</strong></div>
                                                <div><span>Tahun</span><strong>{{ $tkd->tahun ?? '-' }}</strong></div>
                                                <div><span>Tgl Akhir Penawaran</span><strong>{{ $tkd->tgl_akhir_penawaran ? date('d-m-Y H:i', strtotime($tkd->tgl_akhir_penawaran)) : '-' }}</strong></div>
                                            </div>

                                            <div class="mt-3">
                                                <div class="small text-muted mb-1">Keterangan</div>
                                                <div class="asset-description">{{ $tkd->keterangan ?? '-' }}</div>
                                            </div>

                                            <div class="mt-auto pt-3">
                                                <div class="d-flex flex-wrap justify-content-end" style="gap: 8px;">
                                                    <a href="{{ route('tkd.edit', $tkd->id) }}"
                                                        class="btn btn-sm btn-info btn-icon">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    @php
                                                        $currentRole = strtolower((string) (auth()->user()->role_name ?? ''));
                                                        $canApprove = in_array($currentRole, ['super-admin', 'admin_ho']) && strtoupper((string) ($tkd->status ?? '')) === 'WAITING APPROVAL';
                                                    @endphp
                                                    @if ($canApprove)
                                                        <form action="{{ route('tkd.approve', $tkd->id) }}" method="POST">
                                                            @csrf
                                                            <button class="btn btn-sm btn-success btn-icon" type="submit"
                                                                onclick="return confirm('Approve barang ini dan ubah status menjadi OPEN?');">
                                                                <i class="fas fa-check"></i> Approve
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('tkd.destroy', $tkd->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger btn-icon confirm-delete"
                                                            type="submit">
                                                            <i class="fas fa-times"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $tkds->withQueryString()->links() }}
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
            $('.import').click(function(event) {
                event.stopPropagation();
                $('.show-import').slideToggle('fast');
                $('.show-search').hide();
            });
            $('.search').click(function(event) {
                event.stopPropagation();
                $('.show-search').slideToggle('fast');
                $('.show-import').hide();
            });
            $('#file-upload').change(function() {
                var file = $('#file-upload')[0].files[0].name;
                $(this).prev('label').text(file);
            });
        });
    </script>
@endpush
@push('customStyle')
    <link rel="stylesheet" href="/assets/css/select2.min.css">
    <style>
        .asset-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.25rem;
        }

        .asset-card {
            overflow: hidden;
            border-radius: 16px;
            background: #fff;
        }

        .asset-card-image-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 3;
            background: linear-gradient(135deg, #f4f7fb 0%, #edf1f7 100%);
            overflow: hidden;
        }

        .asset-card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .asset-title {
            font-size: 1rem;
            line-height: 1.3;
            font-weight: 700;
        }

        .asset-meta {
            display: grid;
            gap: .6rem;
            margin-top: .5rem;
        }

        .asset-meta div {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            font-size: .88rem;
        }

        .asset-meta span {
            color: #6c757d;
            flex: 0 0 auto;
        }

        .asset-meta strong {
            text-align: right;
            color: #343a40;
            font-weight: 600;
        }

        .asset-description {
            font-size: .9rem;
            color: #495057;
            line-height: 1.5;
        }
    </style>
@endpush

