@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Table</h1>
        </div>
        <div class="section-body">
            <h2 class="section-title">Tambah Pejabat</h2>
            <div class="card">
                <div class="card-header">
                    <h4>Validasi Tambah Data</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('pejabat.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Nama Karyawan</label>
                            <input type="text" id="nama_karyawan" name="nama_karyawan"
                                class="form-control @error('nama_karyawan') is-invalid @enderror"
                                placeholder="Masukan Nama Karyawan" autocomplete="off">
                            @error('nama_karyawan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Jabatan</label>
                            <select class="form-control select2 @error('id_jabatan') is-invalid @enderror" name="id_jabatan"
                                data-id="select-jabatan" id="id_jabatan">
                                <option value="">Piih Jabatan</option>
                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id }}">
                                        {{ $jabatan->jabatan }}</option>
                                @endforeach
                            </select>
                            @error('id_jabatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <!-- OPD removed from schema; field omitted -->
                        <div class="form-group">
                            <label>NIK</label>
                            <input type="text" id="nik" name="nik"
                                class="form-control @error('nik') is-invalid @enderror"
                                placeholder="Masukan NIK" autocomplete="off">
                            @error('nik')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <input type="text" id="gender" name="gender"
                                class="form-control @error('gender') is-invalid @enderror" placeholder="Masukan Gender"
                                autocomplete="off">
                            @error('gender')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary">Submit</button>
                    <a class="btn btn-secondary" href="{{ route('pejabat.index') }}">Cancel</a>
                </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@push('customScript')
    <script src="/assets/js/select2.min.js"></script>
@endpush

@push('customStyle')
    <link rel="stylesheet" href="/assets/css/select2.min.css">
@endpush
