@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tabel Aset Lelang</h1>
        </div>
        <div class="section-body">
            <h2 class="section-title">Ubah Harga</h2>
            <div class="card">
                <div class="card-header">
                    <h4>Ubah Data</h4>
                </div>
                <div class="card-body">
                    <form id="edit-tkd-form" action="{{ route('tkd.update', $tkd) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @php
                            $roleName = strtolower((string) (auth()->user()->role_name ?? ''));
                            $canEditHarga = in_array($roleName, ['super-admin', 'admin_ho']);
                            $canApproveTkd = in_array($roleName, ['super-admin', 'admin_ho']);
                            $isWaitingApproval = strtoupper((string) $tkd->status) === 'WAITING APPROVAL';
                        @endphp
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Aset ID</label>
                                <input type="text" id="aset_id" name="aset_id"
                                    class="form-control @error('aset_id') is-invalid @enderror"
                                    value="{{ old('aset_id', $tkd->aset_id) }}" autocomplete="off">
                                @error('aset_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Status</label>
                                <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="">Pilih Status</option>
                                    @foreach ($statusOptions as $statusOption)
                                        <option value="{{ $statusOption->status_name }}" @selected(old('status', $tkd->status) == $statusOption->status_name)>
                                            {{ $statusOption->status_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Branch</label>
                                <select class="form-control select2 @error('id_branch') is-invalid @enderror"
                                    id="id_branch" name="id_branch_disabled" disabled>
                                    <option value="">Pilih Branch</option>
                                    @foreach ($branches as $branch)
                                        <option @selected($branch->id == $tkd->id_branch) value="{{ $branch->id }}">
                                            {{ $branch->branch }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="id_branch" value="{{ $tkd->id_branch }}">
                                @error('id_branch')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Kondisi</label>
                                <input type="text" id="kondisi" name="kondisi"
                                    class="form-control @error('kondisi') is-invalid @enderror"
                                    value="{{ old('kondisi', $tkd->kondisi) }}" autocomplete="off">
                                @error('kondisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Merk</label>
                                <input type="text" id="merk" name="merk" class="form-control @error('merk') is-invalid @enderror"
                                    value="{{ old('merk', $tkd->merk) }}" autocomplete="off">
                                @error('merk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Kategori</label>
                                <input type="text" id="kategori" name="kategori" class="form-control @error('kategori') is-invalid @enderror"
                                    value="{{ old('kategori', $tkd->kategori) }}" autocomplete="off">
                                @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Lokasi</label>
                                <input type="text" id="lokasi" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                                    value="{{ old('lokasi', $tkd->lokasi) }}" autocomplete="off">
                                @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Kelipatan</label>
                                <input type="number" id="kelipatan" name="kelipatan"
                                    class="form-control @error('kelipatan') is-invalid @enderror"
                                    value="{{ old('kelipatan', $tkd->kelipatan) }}" autocomplete="off">
                                @error('kelipatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Harga Dasar</label>
                                @if ($canEditHarga)
                                    <input type="text" id="harga_dasar" name="harga_dasar"
                                        class="form-control @error('harga_dasar') is-invalid @enderror"
                                        value="{{ old('harga_dasar', $tkd->harga_dasar) }}" autocomplete="off">
                                    @error('harga_dasar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @else
                                    <input type="text" class="form-control" value="{{ $tkd->harga_dasar ? 'Rp ' . number_format((int) $tkd->harga_dasar, 0, ',', '.') : '-' }}" disabled>
                                    <input type="hidden" name="harga_dasar" value="{{ $tkd->harga_dasar }}">
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label>Tahun</label>
                                <input type="number" id="tahun" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
                                    value="{{ old('tahun', $tkd->tahun) }}" autocomplete="off">
                                @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Keterangan</label>
                                <input type="text" id="keterangan" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                    value="{{ old('keterangan', $tkd->keterangan) }}" autocomplete="off">
                                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Tanggal Mulai Penawaran</label>
                                <input type="datetime-local" id="tgl_start_penawaran" name="tgl_start_penawaran"
                                    class="form-control @error('tgl_start_penawaran') is-invalid @enderror"
                                    value="{{ old('tgl_start_penawaran', $tkd->tgl_start_penawaran ? date('Y-m-d\\TH:i', strtotime($tkd->tgl_start_penawaran)) : '') }}">
                                @error('tgl_start_penawaran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Tanggal Akhir Penawaran</label>
                                <input type="datetime-local" id="tgl_akhir_penawaran" name="tgl_akhir_penawaran"
                                    class="form-control @error('tgl_akhir_penawaran') is-invalid @enderror"
                                    value="{{ old('tgl_akhir_penawaran', $tkd->tgl_akhir_penawaran ? date('Y-m-d\\TH:i', strtotime($tkd->tgl_akhir_penawaran)) : '') }}">
                                @error('tgl_akhir_penawaran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Foto</label>
                                <input type="file" id="foto" name="foto" class="form-control @error('foto') is-invalid @enderror"
                                    onchange="previewImage();">
                                @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="text-center mt-2">
                                    <img id="image-preview" src="{{ $tkd->foto ? asset('storage/' . $tkd->foto) : '' }}" alt="your image" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-right">
                    @if ($canApproveTkd && $isWaitingApproval)
                        <form action="{{ route('tkd.approve', $tkd) }}" method="post" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success"
                                onclick="return confirm('Approve barang ini dan ubah status menjadi OPEN?')">
                                Approve
                            </button>
                        </form>
                    @endif
                    <button type="submit" class="btn btn-primary" form="edit-tkd-form">Kirim</button>
                    <a class="btn btn-secondary" href="{{ route('tkd.index') }}">Batal</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('customScript')
    <script src="/assets/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('.select2').select2({
                width: '100%'
            });

            $('#harga_dasar').mask('000,000,000,000,000', {
                reverse: true
            });

            $('form').on('submit', function() {
                var harga_dasar = $('#harga_dasar').val().replace(/,/g, '');
                $('#harga_dasar').val(harga_dasar);
            });
        });

        function previewImage() {
            var input = document.getElementById('foto');
            var preview = document.getElementById('image-preview');
            if (input && input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    preview.style.maxWidth = '200px';
                    preview.style.margin = '10px auto';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush

@push('customStyle')
    <style>
        .form-group { margin-bottom: 1rem; }
        #image-preview {
            display: block;
            max-width: 200px;
            margin: 10px auto;
            box-shadow: 0 4px 8px rgba(0,0,0,0.12);
            border-radius: 6px;
        }
        .card .card-body { padding: 1.25rem; }
    </style>
    <link rel="stylesheet" href="/assets/css/select2.min.css" />
@endpush
