@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Upload Barang Lelang</h1>
        </div>
        <div class="section-body">
            <h2 class="section-title">Tambah Barang Lelang Baru</h2>
            <div class="card">
                <div class="card-header">
                    <h4>Form Input Data Barang Lelang</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Validasi Gagal!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <form action="{{ route('upload.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ID Aset</label>
                                    <input type="text" id="aset_id" name="aset_id"
                                        class="form-control @error('aset_id') is-invalid @enderror"
                                        placeholder="Masukan ID Aset" value="{{ old('aset_id') }}" autocomplete="off">
                                    @error('aset_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Lokasi (Branch) <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('id_branch') is-invalid @enderror"
                                        name="id_branch" id="id_branch" required>
                                        <option value="">Pilih Lokasi</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}" data-branch-name="{{ $branch->branch }}" @selected(old('id_branch') == $branch->id)>
                                                {{ $branch->branch }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="lokasi" name="lokasi" value="{{ old('lokasi') }}">
                                    @error('id_branch')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Barang</label>
                                    <input type="text" id="nama_barang" name="nama_barang"
                                        class="form-control @error('nama_barang') is-invalid @enderror"
                                        placeholder="Masukan Nama Barang" value="{{ old('nama_barang') }}" autocomplete="off">
                                    @error('nama_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kondisi</label>
                                    <select class="form-control @error('kondisi') is-invalid @enderror" name="kondisi" id="kondisi">
                                        <option value="">Pilih Kondisi</option>
                                        <option value="Baik" @selected(old('kondisi') == 'Baik')>Baik</option>
                                        <option value="Sedang" @selected(old('kondisi') == 'Sedang')>Sedang</option>
                                        <option value="Rusak" @selected(old('kondisi') == 'Rusak')>Rusak</option>
                                    </select>
                                    @error('kondisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kategori <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('kategori') is-invalid @enderror"
                                        name="kategori" id="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->name }}" @selected(old('kategori') == $category->name)>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Merk <span class="text-danger">*</span></label>
                                    <input type="text" id="merk" name="merk"
                                        class="form-control @error('merk') is-invalid @enderror"
                                        placeholder="Masukan Merk" value="{{ old('merk') }}" autocomplete="off" required>
                                    @error('merk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Harga Dasar</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" id="harga_dasar" name="harga_dasar"
                                            class="form-control money-input @error('harga_dasar') is-invalid @enderror"
                                            placeholder="Masukan Harga Dasar"
                                            value="{{ old('harga_dasar') !== null && old('harga_dasar') !== '' ? number_format((int) preg_replace('/[^0-9]/', '', old('harga_dasar')), 0, ',', '.') : '' }}"
                                            autocomplete="off" inputmode="numeric">
                                    </div>
                                    @error('harga_dasar')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kelipatan</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" id="kelipatan" name="kelipatan"
                                            class="form-control money-input @error('kelipatan') is-invalid @enderror"
                                            placeholder="Masukan Kelipatan"
                                            value="{{ old('kelipatan') !== null && old('kelipatan') !== '' ? number_format((int) preg_replace('/[^0-9]/', '', old('kelipatan')), 0, ',', '.') : '' }}"
                                            autocomplete="off" inputmode="numeric">
                                    </div>
                                    @error('kelipatan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tahun</label>
                                    <select class="form-control @error('tahun') is-invalid @enderror" name="tahun" id="tahun">
                                        <option value="">Pilih Tahun</option>
                                        @foreach ($tahuns as $tahun)
                                            <option value="{{ $tahun->tahun }}" @selected(old('tahun') == $tahun->tahun)>
                                                {{ $tahun->tahun }}</option>
                                        @endforeach
                                    </select>
                                    @error('tahun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Mulai Penawaran</label>
                                    <input type="datetime-local" id="tgl_start_penawaran" name="tgl_start_penawaran"
                                        class="form-control @error('tgl_start_penawaran') is-invalid @enderror"
                                        value="{{ old('tgl_start_penawaran') ? \Carbon\Carbon::parse(old('tgl_start_penawaran'))->format('Y-m-d\\TH:i') : '' }}">
                                    @error('tgl_start_penawaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Akhir Penawaran</label>
                                    <input type="datetime-local" id="tgl_akhir_penawaran" name="tgl_akhir_penawaran"
                                        class="form-control @error('tgl_akhir_penawaran') is-invalid @enderror"
                                        value="{{ old('tgl_akhir_penawaran') ? \Carbon\Carbon::parse(old('tgl_akhir_penawaran'))->format('Y-m-d\\TH:i') : '' }}">
                                    @error('tgl_akhir_penawaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea id="keterangan" name="keterangan"
                                        class="form-control @error('keterangan') is-invalid @enderror"
                                        placeholder="Masukan Keterangan" autocomplete="off" rows="4">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Foto</label>
                                    <input type="file" id="foto" name="foto" class="form-control @error('foto') is-invalid @enderror"
                                        accept="image/*">
                                    <small class="form-text text-muted">Format: JPEG, PNG, JPG (Max: 10MB)</small>
                                    @error('foto')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a class="btn btn-secondary" href="{{ route('tkd.index') }}">Batal</a>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2();

            // Function to update lokasi field
            function updateLokasi() {
                var selectedOption = $('#id_branch').find('option:selected');
                var branchName = selectedOption.data('branch-name');
                
                console.log('Updating lokasi...');
                console.log('Selected option:', selectedOption.text());
                console.log('Branch name from data:', branchName);
                
                if (branchName) {
                    $('#lokasi').val(branchName);
                } else {
                    // Fallback: use the option text as branch name
                    var optionText = selectedOption.text().trim();
                    if (optionText && optionText !== 'Pilih Lokasi') {
                        $('#lokasi').val(optionText);
                    }
                }
                
                console.log('Lokasi field now contains:', $('#lokasi').val());
            }

            // Populate lokasi on Select2 change (proper event for Select2)
            $('#id_branch').on('select2:select', function() {
                console.log('select2:select triggered');
                updateLokasi();
            });

            // Also listen to regular change event as fallback
            $('#id_branch').on('change', function() {
                console.log('change event triggered');
                updateLokasi();
            });

            // Initialize lokasi on page load if branch is already selected
            setTimeout(function() {
                if ($('#id_branch').val()) {
                    console.log('Page load: initializing lokasi');
                    updateLokasi();
                }
            }, 500);

            function formatMoney(value) {
                var digits = String(value || '').replace(/[^0-9]/g, '');
                if (!digits) {
                    return '';
                }

                return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            $('.money-input').each(function() {
                $(this).val(formatMoney($(this).val()));
            });

            $('.money-input').on('input', function() {
                this.value = formatMoney(this.value);
            });

            $('form').on('submit', function(e) {
                console.log('Form submit triggered');
                
                // Ensure lokasi is populated before submit
                if (!$('#lokasi').val() && $('#id_branch').val()) {
                    console.log('Lokasi was empty, attempting to populate...');
                    updateLokasi();
                }

                // Verify all required fields
                if (!$('#id_branch').val()) {
                    alert('Lokasi (Branch) harus dipilih!');
                    e.preventDefault();
                    return false;
                }
                
                if (!$('#lokasi').val()) {
                    alert('Lokasi field gagal terisi otomatis. Silakan refresh halaman dan coba lagi.');
                    e.preventDefault();
                    return false;
                }

                console.log('Final lokasi value on submit:', $('#lokasi').val());

                // Strip formatting from money inputs
                $('.money-input').each(function() {
                    this.value = String(this.value || '').replace(/[^0-9]/g, '');
                });

                console.log('Form validation passed, allowing submit');
            });
        });
    </script>
@endpush
