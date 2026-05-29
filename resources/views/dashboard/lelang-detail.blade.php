<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Lelang - {{ $detail->nama_tampil }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR1XCbMQv3Xipma34MD+dH/1fQ784/j6C/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard-user.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lelang-detail.css') }}">
</head>

<body class="lelang-detail-page">
    <div class="midi-wrapper">
        <div class="midi-shell">
            @include('dashboard.partials.sidebar')

            <main class="midi-main">
                <section class="midi-content lelang-detail-content">
                    <div class="lelang-detail-wrap">
                        <div class="lelang-detail-breadcrumb">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                            <span class="mx-1">/</span>
                            <a href="{{ route('dashboard') }}">Lelang Aktif</a>
                            <span class="mx-1">/</span>
                            <span>{{ $detail->nama_tampil }}</span>
                        </div>

                        <div class="lelang-detail-shell">
                            <section>
                                <div class="lelang-gallery">
                                    <div class="lelang-gallery-main">
                                        <img src="{{ $detail->foto_url }}" alt="Foto {{ $detail->nama_tampil }}">
                                    </div>
                                </div>

                                <div class="lelang-panel penawaran-detail-panel">
                                    <h3 class="penawaran-detail-title">Detail Penawaran</h3>
                                    <div class="penawaran-detail-table-wrap">
                                        <table class="penawaran-detail-table">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>Nilai Penawaran</th>
                                                    <th>Status</th>
                                                    <th>Tanggal Penawaran</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($riwayatPenawaran as $riwayat)
                                                    <tr>
                                                        <td>{{ $riwayat->nama_tersensor }}</td>
                                                        <td>{{ $riwayat->nilai_penawaran_rupiah }}</td>
                                                        <td>
                                                            <span class="penawaran-status {{ strtolower($riwayat->status_label) === 'gugur' ? 'is-gugur' : (strtolower($riwayat->status_label) === 'pemenang lelang' ? 'is-pemenang' : 'is-ditinjau') }}">
                                                                {{ $riwayat->status_label }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $riwayat->tanggal_penawaran_label }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="penawaran-empty">Belum ada penawaran untuk barang ini.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </section>

                            <aside class="lelang-info">
                                <div class="lelang-label">{{ $detail->lokasi ?? 'Barang Lelang' }}</div>
                                <h1 class="lelang-title">{{ $detail->nama_tampil }}</h1>


                                <div class="lelang-price-grid">
                                    <div class="lelang-price-box">
                                        <div class="caption">Harga Dasar</div>
                                        <div class="value">{{ $detail->harga_dasar_rupiah }}</div>
                                    </div>
                                    <div class="lelang-price-box">
                                        <div class="caption">Kelipatan</div>
                                        <div class="value">{{ $detail->kelipatan ? 'Rp ' . number_format((int) $detail->kelipatan, 0, ',', '.') : '-' }}</div>
                                    </div>
                                    <div class="lelang-price-box">
                                        <div class="caption">Mulai Penawaran</div>
                                        <div class="value">{{ $detail->tgl_start_penawaran_label }}</div>
                                    </div>
                                    <div class="lelang-price-box">
                                        <div class="caption">Akhir Penawaran</div>
                                        <div class="value">{{ $detail->tgl_akhir_penawaran_label }}</div>
                                    </div>
                                </div>


                                <div class="lelang-countdown-box" id="countdown-box" data-end="{{ $detail->tgl_akhir_penawaran_iso }}">
                                    <div class="countdown-icon">
                                        <i class="fas fa-stopwatch"></i>
                                    </div>
                                    <div class="countdown-copy">
                                        <div class="caption">Sisa Waktu Penawaran</div>
                                        <div class="value" id="countdown-value">-- : -- : --</div>
                                        <small class="text-muted d-block">Berakhir {{ $detail->tgl_akhir_penawaran_label }}</small>
                                    </div>
                                </div>

                                <div class="lelang-meta-grid">
                                    <div class="lelang-meta-item">
                                        <div class="caption">Aset ID</div>
                                        <div class="value">{{ $detail->aset_id ?? '-' }}</div>
                                    </div>
                                    <div class="lelang-meta-item">
                                        <div class="caption">Tahun</div>
                                        <div class="value">{{ $detail->tahun ?? '-' }}</div>
                                    </div>
                                    <div class="lelang-meta-item">
                                        <div class="caption">Status</div>
                                        <div class="value">{{ $detail->status ?? '-' }}</div>
                                    </div>
                                    <div class="lelang-meta-item">
                                        <div class="caption">Merk</div>
                                        <div class="value">{{ $detail->merk ?? '-' }}</div>
                                    </div>
                                    <div class="lelang-meta-item">
                                        <div class="caption">Kondisi</div>
                                        <div class="value">{{ $detail->kondisi ?? '-' }}</div>
                                    </div>
                                    <div class="lelang-meta-item">
                                        <div class="caption">Lokasi</div>
                                        <div class="value">{{ $detail->lokasi ?? '-' }}</div>
                                    </div>
                                </div>

                                @php($isLelangClosed = strtoupper((string) ($detail->status ?? '')) === 'CLOSED')

                                @auth
                                    @php($isUserVerified = (bool) (auth()->user()->isverified ?? false))
                                    @if (!empty($isCurrentUserWinner))
                                        <a href="https://wa.me/62895411305226" class="lelang-cta is-winner" target="_blank" rel="noopener noreferrer">
                                            Selamat Anda Terpilih Menjadi Pemenang Lelang Hubungi Whatsapp Panitia
                                        </a>
                                    @elseif ($isLelangClosed)
                                        <button type="button" class="lelang-cta is-disabled" disabled>Penawaran Berakhir</button>
                                    @elseif (!$isUserVerified)
                                        <button type="button" id="btn-approval-pending" class="lelang-cta">Konfirmasi Penawaran</button>
                                    @else
                                        <button type="button" id="toggle-penawaran" class="lelang-cta">Ikuti Lelang Sekarang</button>

                                        <form id="penawaran-form" class="penawaran-form-wrap" hidden method="POST" action="{{ route('dashboard.penawaran.store') }}"
                                            data-harga-dasar="{{ (int) ($detail->harga_dasar ?? 0) }}"
                                            data-kelipatan="{{ (int) ($detail->kelipatan ?? 0) }}">
                                            @csrf
                                            <input type="hidden" name="idfk_barang" value="{{ $detail->id }}">
                                            <input type="hidden" name="aset_id" value="{{ $detail->id_tkd ?? $detail->aset_id ?? '' }}">
                                            <input type="hidden" name="nilai_penawaran" id="nilai-penawaran-hidden" value="">
                                            <input type="hidden" name="keterangan" id="keterangan-hidden" value="">

                                            <label for="nominal-penawaran-number" class="penawaran-label">Masukkan Harga Penawaran anda</label>
                                            <div class="penawaran-form-grid">
                                                <div class="penawaran-number-row">
                                                    <div class="penawaran-number-group">
                                                        <input type="text" id="nominal-penawaran-number" class="penawaran-number-input"
                                                            inputmode="numeric" autocomplete="off" value="">
                                                        <div class="penawaran-spin-buttons">
                                                            <button type="button" id="nominal-step-up" class="spin-btn" aria-label="Tambah nominal">
                                                                <i class="fas fa-chevron-up"></i>
                                                            </button>
                                                            <button type="button" id="nominal-step-down" class="spin-btn" aria-label="Kurangi nominal">
                                                                <i class="fas fa-chevron-down"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="penawaran-note-row">
                                                    <label for="keterangan-penawaran" class="penawaran-label">Masukkan Catatan</label>
                                                    <input type="text" id="keterangan-penawaran" class="penawaran-note-input"
                                                        autocomplete="off" placeholder="Tuliskan catatan jika ada">
                                                </div>
                                            </div>
                                            <small class="penawaran-hint" id="penawaran-hint"></small>
                                            <div class="penawaran-actions">
                                                <button type="button" id="btn-lanjut-penawaran" class="btn-lanjut-penawaran">Konfirmasi Penawaran</button>
                                            </div>
                                        </form>
                                    @endif
                                @else
                                    @if ($isLelangClosed)
                                        <button type="button" class="lelang-cta is-disabled" disabled>Penawaran Berakhir</button>
                                    @else
                                        <a href="{{ route('login') }}" class="lelang-cta">Login untuk Ikut Lelang</a>
                                    @endif
                                @endauth
                            </aside>
                        </div>
                    </div>
                </section>

                <footer class="midi-footer">
                    <span>&copy; {{ date('Y') }} PT. Midi Utama Indonesia Tbk</span>
                    <span>Sistem Informas/Lelang Aset Terintegrasi</span>
                </footer>
            </main>
        </div>
    </div>
    <script>
        (function() {
            var countdownBox = document.getElementById('countdown-box');
            var countdownValue = document.getElementById('countdown-value');

            if (!countdownBox || !countdownValue) {
                return;
            }

            var endValue = countdownBox.getAttribute('data-end');

            if (!endValue) {
                countdownValue.textContent = 'Jadwal belum ditentukan';
                return;
            }

            var endTime = new Date(endValue).getTime();

            function pad(number) {
                return String(number).padStart(2, '0');
            }

            function renderCountdown() {
                var now = new Date().getTime();
                var distance = endTime - now;

                if (distance <= 0) {
                    countdownValue.textContent = '00 : 00 : 00';
                    countdownBox.classList.add('expired');
                    return;
                }

                var hours = Math.floor(distance / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownValue.textContent = pad(hours) + ' : ' + pad(minutes) + ' : ' + pad(seconds);
            }

            renderCountdown();
            setInterval(renderCountdown, 1000);
        })();

        (function() {
            var approvalBtn = document.getElementById('btn-approval-pending');

            if (!approvalBtn) {
                return;
            }

            var approvalUrl = '{{ route('approval.pending') }}';

            function goToApprovalPage() {
                window.location.href = approvalUrl;
            }

            approvalBtn.addEventListener('click', function() {
                if (window.Swal && typeof window.Swal.fire === 'function') {
                    window.Swal.fire({
                        title: 'Akun Belum Diverifikasi',
                        text: 'Akun Anda masih menunggu verifikasi internal. Silakan logout atau batal.',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Logout',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#0e6cc7',
                        cancelButtonColor: '#6c757d'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            var logoutForm = document.createElement('form');
                            logoutForm.method = 'POST';
                            logoutForm.action = '{{ route('logout') }}';

                            var csrfToken = document.querySelector('meta[name="csrf-token"]');
                            if (csrfToken && csrfToken.getAttribute('content')) {
                                var tokenInput = document.createElement('input');
                                tokenInput.type = 'hidden';
                                tokenInput.name = '_token';
                                tokenInput.value = csrfToken.getAttribute('content');
                                logoutForm.appendChild(tokenInput);
                            }

                            document.body.appendChild(logoutForm);
                            logoutForm.submit();
                        }
                    });

                    return;
                }

                if (window.confirm('Akun belum diverifikasi. Klik OK untuk logout atau Cancel untuk batal.')) {
                    var fallbackLogoutForm = document.createElement('form');
                    fallbackLogoutForm.method = 'POST';
                    fallbackLogoutForm.action = '{{ route('logout') }}';

                    var fallbackToken = document.querySelector('meta[name="csrf-token"]');
                    if (fallbackToken && fallbackToken.getAttribute('content')) {
                        var fallbackTokenInput = document.createElement('input');
                        fallbackTokenInput.type = 'hidden';
                        fallbackTokenInput.name = '_token';
                        fallbackTokenInput.value = fallbackToken.getAttribute('content');
                        fallbackLogoutForm.appendChild(fallbackTokenInput);
                    }

                    document.body.appendChild(fallbackLogoutForm);
                    fallbackLogoutForm.submit();
                }
            });
        })();

        (function() {
            var toggleBtn = document.getElementById('toggle-penawaran');
            var formWrap = document.getElementById('penawaran-form');
            var nominalInput = document.getElementById('nominal-penawaran-number');
            var stepUpBtn = document.getElementById('nominal-step-up');
            var stepDownBtn = document.getElementById('nominal-step-down');
            var keteranganInput = document.getElementById('keterangan-penawaran');
            var hint = document.getElementById('penawaran-hint');
            var lanjutBtn = document.getElementById('btn-lanjut-penawaran');
            var nilaiHidden = document.getElementById('nilai-penawaran-hidden');
            var keteranganHidden = document.getElementById('keterangan-hidden');

            if (!toggleBtn || !formWrap || !nominalInput || !stepUpBtn || !stepDownBtn || !keteranganInput || !hint || !lanjutBtn || !nilaiHidden || !keteranganHidden) {
                return;
            }

            var hargaDasar = Number(formWrap.getAttribute('data-harga-dasar') || 0);
            var kelipatan = Number(formWrap.getAttribute('data-kelipatan') || 0);
            var nominalStep = kelipatan > 0 ? kelipatan : 1;

            function formatRupiah(value) {
                return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
            }

            function parseNominal(raw) {
                var digits = String(raw || '').replace(/\D/g, '');
                return digits ? Number(digits) : 0;
            }

            function getNominalMax() {
                if (kelipatan > 0) {
                    return hargaDasar + (kelipatan * 100);
                }

                return hargaDasar + 10000000;
            }

            function getNominalValue() {
                return parseNominal(nominalInput.value || 0);
            }

            function syncInputDisplay() {
                var nominal = getNominalValue();
                nominalInput.value = nominal ? formatRupiah(nominal) : '';
            }

            function isValidNominal(value) {
                if (value < hargaDasar) {
                    return false;
                }

                if (kelipatan > 0 && (value - hargaDasar) % kelipatan !== 0) {
                    return false;
                }

                return true;
            }

            function validateAndRender() {
                var nominal = getNominalValue();

                if (!nominal) {
                    hint.textContent = 'Nominal tidak boleh kosong.';
                    hint.classList.add('is-error');
                    lanjutBtn.classList.add('is-disabled');
                    return;
                }

                if (!isValidNominal(nominal)) {
                    hint.textContent = kelipatan > 0
                        ? 'Nominal harus mengikuti kelipatan harga dasar.'
                        : 'Nominal harus minimal sama dengan harga dasar.';
                    hint.classList.add('is-error');
                    lanjutBtn.classList.add('is-disabled');
                    return;
                }

                hint.textContent = 'Nominal valid: ' + formatRupiah(nominal) + '.';
                hint.classList.remove('is-error');
                lanjutBtn.classList.remove('is-disabled');
            }

            function getKeteranganValue() {
                return String(keteranganInput.value || '').trim();
            }

            function setDefaultNominal() {
                nominalInput.value = formatRupiah(hargaDasar);
                validateAndRender();
            }

            function setNominalValue(value) {
                var bounded = Math.max(hargaDasar, Math.min(getNominalMax(), value));

                if (kelipatan > 0) {
                    var offset = bounded - hargaDasar;
                    var remainder = offset % kelipatan;
                    if (remainder !== 0) {
                        bounded -= remainder;
                    }
                }

                nominalInput.value = formatRupiah(bounded);
                validateAndRender();
            }

            toggleBtn.addEventListener('click', function() {
                formWrap.hidden = !formWrap.hidden;
                if (!formWrap.hidden) {
                    setDefaultNominal();
                    nominalInput.focus();
                }
            });

            nominalInput.addEventListener('input', function() {
                syncInputDisplay();
                validateAndRender();
            });

            nominalInput.addEventListener('focus', function() {
                var nominal = getNominalValue();
                nominalInput.value = nominal ? String(nominal) : '';
            });

            keteranganInput.addEventListener('input', function() {
                if (keteranganInput.value.length > 160) {
                    keteranganInput.value = keteranganInput.value.slice(0, 160);
                }
            });

            nominalInput.addEventListener('blur', function() {
                syncInputDisplay();
                validateAndRender();
            });

            nominalInput.addEventListener('keydown', function(event) {
                var current = getNominalValue() || hargaDasar;

                if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    setNominalValue(current + nominalStep);
                }

                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    setNominalValue(current - nominalStep);
                }
            });

            stepUpBtn.addEventListener('click', function() {
                var current = getNominalValue() || hargaDasar;
                setNominalValue(current + nominalStep);
            });

            stepDownBtn.addEventListener('click', function() {
                var current = getNominalValue() || hargaDasar;
                setNominalValue(current - nominalStep);
            });

            lanjutBtn.addEventListener('click', function(event) {
                var nominal = getNominalValue();
                var keterangan = getKeteranganValue();

                if (!isValidNominal(nominal)) {
                    event.preventDefault();
                    validateAndRender();
                    return;
                }

                nilaiHidden.value = String(nominal);
                keteranganHidden.value = keterangan;

                var submitForm = function() {
                    formWrap.submit();
                };

                if (window.Swal && typeof window.Swal.fire === 'function') {
                    window.Swal.fire({
                        title: 'Konfirmasi Penawaran',
                        text: 'Pastikan nominal penawaran sudah benar sebelum disimpan.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, simpan',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#0e6cc7',
                        cancelButtonColor: '#6c757d'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            submitForm();
                        }
                    });
                    return;
                }

                if (window.confirm('Konfirmasi penawaran ini?')) {
                    submitForm();
                }
            });
        })();
    </script>
</body>

</html>
