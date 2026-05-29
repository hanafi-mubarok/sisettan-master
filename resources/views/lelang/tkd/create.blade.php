@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Table</h1>
        </div>
        <div class="section-body">
            <h2 class="section-title">Tambah Harga Dasar</h2>
            <div class="card">
                <div class="card-header">
                    <h4>Validasi Tambah Data</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('tkd.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Lokasi (Branch)<span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('lokasi') is-invalid @enderror"
                                name="lokasi" id="lokasi">
                                <option value="">Pilih Lokasi</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" data-id-branch="{{ $branch->id }}">
                                        {{ $branch->branch }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="id_branch" id="id_branch_hidden" value="{{ $branchIdFromDaerah }}" />
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Nama Barang<span class="text-danger">*</span></label>
                            <input type="text" id="nama_barang" name="nama_barang"
                                class="form-control @error('nama_barang') is-invalid @enderror"
                                placeholder="Masukan Nama Barang" value="{{ old('nama_barang') }}" autocomplete="off">
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Kondisi<span class="text-danger">*</span></label>
                            <input type="text" id="kondisi" name="kondisi"
                                class="form-control @error('kondisi') is-invalid @enderror"
                                placeholder="Masukan Kondisi" value="{{ old('kondisi') }}" autocomplete="off">
                            @error('kondisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Merk<span class="text-danger">*</span></label>
                            <input type="text" id="merk" name="merk" class="form-control @error('merk') is-invalid @enderror"
                                placeholder="Masukan Merk" autocomplete="off">
                            @error('merk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Kategori<span class="text-danger">*</span></label>
                            <input type="text" id="kategori" name="kategori" class="form-control @error('kategori') is-invalid @enderror"
                                placeholder="Masukan Kategori" autocomplete="off">
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Kelipatan<span class="text-danger">*</span></label>
                            <input type="number" id="kelipatan" name="kelipatan" class="form-control @error('kelipatan') is-invalid @enderror"
                                placeholder="Masukan Kelipatan" autocomplete="off">
                            @error('kelipatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Harga Dasar<span class="text-danger">*</span></label>
                            <input type="text" id="harga_dasar" name="harga_dasar"
                                class="form-control @error('harga_dasar') is-invalid @enderror"
                                placeholder="Masukan Harga Dasar" autocomplete="off">
                            @error('harga_dasar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Tahun</label>
                            <input type="number" id="tahun" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
                                placeholder="Masukan Tahun" autocomplete="off">
                            @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="">Pilih Status</option>
                                @foreach ($statusOptions as $statusOption)
                                    <option value="{{ $statusOption->status_name }}" @selected(old('status') == $statusOption->status_name)>
                                        {{ $statusOption->status_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea id="keterangan" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                placeholder="Masukkan Keterangan" autocomplete="off" rows="4"></textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Foto</label>
                            <input type="file" id="foto" name="foto" class="form-control @error('foto') is-invalid @enderror">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary">Submit</button>
                    <a class="btn btn-secondary" href="{{ route('tkd.index') }}">Cancel</a>
                </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('customStyle')
    <link rel="stylesheet" href="/assets/css/select2.min.css">
@endpush

@push('customScript')
    <script src="/assets/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script type="text/javascript">
        // Initialize Select2
        $('#lokasi').select2();
        
        // Set initial value if kelurahanIdFromDaerah exists
        @if ($branchIdFromDaerah)
            $('#lokasi').val('{{ $branchIdFromDaerah }}').trigger('change');
        @endif
        
        // Handle lokasi change - populate id_branch
        $('#lokasi').on('change', function() {
            var selectedId = $(this).val();
            $('#id_branch_hidden').val(selectedId);
        });
        
        $('#harga_dasar').mask('000,000,000,000,000', {
            reverse: true
        });
        $('form').on('submit', function() {
            var harga_dasar = $('#harga_dasar').val().replace(/,/g, '');
            $('#harga_dasar').val(harga_dasar);
        });
    </script>
@endpush
