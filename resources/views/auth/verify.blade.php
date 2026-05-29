@extends('layouts.app')

@push('customStyle')
    <style>
        .main-sidebar {
            display: none !important;
        }
        
        .main-content {
            margin-left: 0 !important;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Menunggu Verifikasi Internal</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Approval</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Akun Anda Sedang Ditinjau</h2>
            <div class="card">
                <div class="card-header">Status Verifikasi Akun</div>

                <div class="card-body">
                    <p class="mb-2">
                        Registrasi Anda berhasil diterima. Akun akan diverifikasi oleh admin/superadmin internal MIDI
                        sebelum dapat mengirim penawaran.
                    </p>
                    <p class="mb-4">
                        Anda tetap bisa login dan mengakses dashboard, namun fitur Konfirmasi Penawaran akan aktif
                        setelah akun disetujui.
                    </p>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-primary">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
