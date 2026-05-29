@extends('layouts.app')
@section('content')
    <section class="section dashboard-page">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <section class="section">
            <div class="container-fluid px-2 px-lg-3 dashboard-fluid">
                <div class="row align-items-stretch dashboard-top-grid">
                    <div class="col-12 col-xl-8 mb-3 mb-xl-0 dashboard-top-stats">
                        <!-- Statistic cards -->
                        <div class="row dashboard-stats-row">
                            <div class="col-12">
                                <!-- Total Pendaftar card -->
                                <div class="card card-statistic-2">
                                    <div class="card-icon bg-danger">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Total Peserta</h4>
                                        </div>
                                        <div class="card-body" id="totalPeserta">
                                            {{ isset($totalPendaftar) ? number_format($totalPendaftar, 0, ',', '.') : '0' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <!-- Total TKD card -->
                                <div class="card card-statistic-2">
                                    <div class="card-icon bg-success">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Total Aset</h4>
                                        </div>
                                        <div class="card-body" id="totalTkd">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <!-- Total Penawaran card -->
                                <div class="card card-statistic-2">
                                    <div class="card-icon bg-warning">
                                        <i class="fas fa-file"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Total Penawaran</h4>
                                        </div>
                                        <div class="card-body" id="totalPenawaran">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 dashboard-top-filter">
                        <!-- Filter section -->
                        <div class="pricing pricing-highlight">
                            <div class="pricing-title">
                                Filter
                            </div>
                            <div class="pricing-padding midi-filter-body">
                                <div class="dashboard-filter-fields">
                                    <div class="form-group mb-0">
                                        <select class="form-control select2" name="tahun_lelang" id="dropdown-item">
                                            <option value="">Tahun Lelang</option>
                                            @foreach ($tahun as $item)
                                                <option @selected($tahunSelected == $item->id) value="{{ $item->id }}"
                                                    data-tahun="{{ $item->tahun }}">
                                                    {{ $item->tahun }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tahun_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-0">
                                        <select class="form-control select2" name="lokasi" id="location-item">
                                            <option value="">Lokasi Barang</option>
                                            @foreach ($lokasiOptions as $item)
                                                <option @selected($lokasiSelected == $item) value="{{ $item }}">
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('lokasi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Detail Barang Lelang</h4>
                        </div>
                        <div class="card-body">
                            <div class="auction-grid">
                                @forelse ($lelangItems as $item)
                                    <div class="auction-item">
                                        <div class="card h-100 shadow-sm auction-card w-100">
                                            <img src="{{ $item->foto_url }}" class="card-img-top auction-image"
                                                alt="Foto {{ $item->kategori }}">
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="mb-1 font-weight-bold">{{ $item->nama_barang }}</h6>
                                                <p class="mb-1 text-muted">{{ $item->kategori }}</p>
                                                <p class="mb-1 text-muted">{{ $item->lokasi }}</p>
                                                <p class="mb-2 font-weight-bold">{{ $item->harga_dasar_rupiah }}</p>
                                                <small class="text-muted mb-3">{{ $item->status_waktu }}</small>
                                                <a href="{{ route('tkd.index') }}" class="btn btn-primary btn-sm mt-auto">Lihat Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-light border mb-0">Belum ada data barang pada tahun yang dipilih.</div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection

@push('customScript')
    <script src="/assets/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            var selectedYearId = {{ $tahunSelected ?? 'null' }};
            var selectedLokasi = @json($lokasiSelected);

            // if no year selected, default to current year (if present in the options)
            if (!selectedYearId) {
                var currentYear = new Date().getFullYear();
                var $opt = $('#dropdown-item option').filter(function() {
                    return $(this).data('tahun') == currentYear;
                }).first();
                if ($opt.length) {
                    selectedYearId = $opt.val();
                    $('#dropdown-item').val(selectedYearId);
                    // persist selection and reload (matches existing behavior)
                    storeSelectedFilters();
                }
            }

            function updateStatistics() {
                // Require a year; lokasi is optional (aggregate across all lokasi when empty)
                if (!selectedYearId) {
                    $('#totalPeserta').text('--');
                    $('#totalTkd').text('--');
                    $('#totalPenawaran').text('--');
                    return;
                }

                $.getJSON('/total-pendaftar', function(data) {
                    $('#totalPeserta').text(data.totalPendaftar || '0');
                });
                $.getJSON('/total-tkd', function(data) {
                    $('#totalTkd').text(data.totalTkd || '0');
                });
                $.getJSON('/total-penawaran', function(data) {
                    $('#totalPenawaran').text(data.totalPenawaran || '0');
                });
            }

            function storeSelectedFilters() {
                $.ajax({
                    url: '{{ route('storeSelectedValues') }}',
                    type: 'POST',
                    data: {
                        'tahun_id': selectedYearId,
                        'lokasi': selectedLokasi,
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        updateStatistics();
                        window.location.reload();
                    }
                });
            }

            $('#dropdown-item').on('change', function() {
                selectedYearId = $(this).find(':selected').val();
                storeSelectedFilters();
            });

            $('#location-item').on('change', function() {
                selectedLokasi = $(this).find(':selected').val();
                storeSelectedFilters();
            });

            if (selectedYearId) {
                $('#dropdown-item').val(selectedYearId);
            }

            if (selectedLokasi) {
                $('#location-item').val(selectedLokasi);
            }

            updateStatistics();

        });
    </script>
@endpush

@push('customStyle')
    <link rel="stylesheet" href="/assets/css/select2.min.css">
    <link rel="stylesheet" href="/assets/css/custom.css">
@endpush
