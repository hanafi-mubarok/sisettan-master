<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Peserta</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard-user.css') }}">
    <style>
        /* Notifications: make list scrollable and clamp long text with toggle */
        .midi-notif-list {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 420px;
            overflow-y: auto;
        }

        .midi-notif-list li {
            display: flex;
            gap: 10px;
            padding: 10px 8px;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            align-items: flex-start;
        }

        .midi-notif-list li i {
            flex: 0 0 28px;
            font-size: 18px;
            color: #6c757d;
            margin-top: 3px;
        }

        .midi-notif-list li .notif-body {
            flex: 1 1 auto;
            min-width: 0; /* allow proper truncation on flex children */
        }

        .midi-notif-list .notif-title {
            display: block;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .midi-notif-list .notif-detail {
            color: #495057;
            font-size: 0.95rem;
            line-height: 1.25;
            overflow: hidden;
            word-break: break-word;
        }

        .midi-notif-list .notif-clamp {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .midi-notif-list .notif-toggle {
            display: none;
            margin-top: 6px;
            font-size: 0.85rem;
            color: #007bff;
            text-decoration: none;
            cursor: pointer;
        }

        .midi-notif-list .notif-detail.expanded {
            display: block;
            -webkit-line-clamp: unset;
            max-height: none;
        }

        .midi-notif-list small {
            display: block;
            margin-top: 6px;
            color: #6c757d;
        }

        /* Small tweak: ensure right column doesn't overflow layout on small screens */
        @media (max-width: 992px) {
            .midi-main-grid { flex-direction: column; }
            .midi-main-right { margin-top: 16px; }
        }
    </style>
</head>

<body>
    <div class="midi-wrapper">
        <div class="midi-shell">
            @include('dashboard.partials.sidebar')

            <main class="midi-main">
                <section class="midi-content">
                    <h1>Selamat Datang, {{ auth()->user()->name }}</h1>

                    <div class="midi-stat-grid">
                        <div class="midi-stat-card">
                            <span>Total Lelang Diikuti</span>
                            <strong>{{ $totalLelangDiikuti }}</strong>
                        </div>
                        <div class="midi-stat-card">
                            <span>Lelang Dimenangkan</span>
                            <strong>{{ $lelangDimenangkan }}</strong>
                        </div>
                        <div class="midi-stat-card">
                            <span>Status Akun</span>
                            <strong class="status">{{ $statusAkun }}</strong>
                        </div>
                    </div>

                    <div class="midi-main-grid">
                        <div class="midi-main-left">
                            <div class="midi-card-box">
                                <h3 class="midi-card-title">
                                    {{ !empty($isPenawaranSayaPage) ? 'Penawaran Saya' : (!empty($isRiwayatPage) ? 'Riwayat Lelang Saya' : 'Daftar Lelang Aktif') }}
                                </h3>
                                <br>
                                <div class="midi-lelang-grid">
                                    @forelse ($lelangAktif as $item)
                                        <article class="midi-item">
                                            <div class="midi-item-media">
                                                <img src="{{ $item->foto_url }}" alt="Foto {{ $item->kategori }}">
                                                <div class="midi-countdown-chip" data-end="{{ !empty($item->tgl_akhir_penawaran) ? date('c', strtotime($item->tgl_akhir_penawaran)) : '' }}">
                                                    <i class="far fa-clock" aria-hidden="true"></i>
                                                    <span class="midi-countdown-text">-- jam lagi</span>
                                                </div>
                                            </div>
                                            <div class="midi-item-body">
                                                <h4>{{ $item->nama_barang }}</h4>
                                                <p>{{ $item->kategori }}</p>
                                                @if (!empty($isPenawaranSayaPage) && !empty($item->penawaran_user_rupiah))
                                                    <p class="harga">Penawaran Menang: {{ $item->penawaran_user_rupiah }}</p>
                                                @endif
                                                <p class="harga">{{ $item->harga_dasar_rupiah }}</p>
                                                <p>{{ $item->status_waktu }}</p>
                                                <br>
                                                @php($isClosed = strtoupper((string) ($item->status ?? '')) === 'CLOSED')
                                                <a href="{{ route('dashboard.lelang.detail', $item->id) }}" class="midi-btn {{ $isClosed ? 'midi-btn-closed' : '' }}">
                                                    {{ $isClosed ? 'Lihat Pemenang Lelang' : 'Ikuti Lelang' }}
                                                </a>
                                            </div>
                                        </article>
                                    @empty
                                        <div class="midi-empty">{{ !empty($isPenawaranSayaPage) ? 'Belum ada aset lelang yang Anda menangkan.' : (!empty($isRiwayatPage) ? 'Belum ada aset yang pernah Anda tawar.' : 'Belum ada data barang lelang aktif.') }}</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="midi-main-right">
                            <div class="midi-card-box">
                                <h3 class="midi-card-title">Notifikasi</h3>
                                <ul class="midi-notif-list">
                                    @forelse ($notifikasi as $item)
                                        <li>
                                            <i class="{{ $item->is_read ? 'far fa-check-circle' : 'far fa-clock' }}"></i>
                                            <div class="notif-body">
                                                <strong class="notif-title">{{ $item->judul ?? $item }}</strong>
                                                @if (!empty($item->detail))
                                                    <div class="notif-detail notif-clamp">{{ $item->detail }}</div>
                                                    <a href="#" class="notif-toggle">Selengkapnya</a>
                                                @endif
                                                @if (!empty($item->created_at))
                                                    <small>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('d M Y H:i') }}</small>
                                                @endif
                                            </div>
                                        </li>
                                    @empty
                                        <li>Tidak ada notifikasi</li>
                                    @endforelse
                                </ul>
                            </div>
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
            var chips = document.querySelectorAll('.midi-countdown-chip');

            if (!chips.length) {
                return;
            }

            function renderCountdown() {
                var now = new Date().getTime();

                chips.forEach(function(chip) {
                    var textEl = chip.querySelector('.midi-countdown-text');
                    var endValue = chip.getAttribute('data-end');

                    if (!textEl || !endValue) {
                        if (textEl) {
                            textEl.textContent = 'Penawaran Berakhir';
                        }
                        chip.classList.add('is-ended');
                        return;
                    }

                    var endTime = new Date(endValue).getTime();

                    if (isNaN(endTime)) {
                        textEl.textContent = 'Penawaran Berakhir';
                        chip.classList.add('is-ended');
                        return;
                    }

                    var diff = endTime - now;

                    if (diff <= 0) {
                        textEl.textContent = 'Penawaran Berakhir';
                        chip.classList.add('is-ended');
                        return;
                    }

                    var hoursLeft = Math.ceil(diff / (1000 * 60 * 60));
                    textEl.textContent = hoursLeft + ' jam lagi';
                    chip.classList.remove('is-ended');
                });
            }

            renderCountdown();
            setInterval(renderCountdown, 60000);
        })();
    </script>
    <script>
        // Notifications: show "Selengkapnya" when detail is clamped
        (function() {
            var notifItems = document.querySelectorAll('.midi-notif-list li');

            notifItems.forEach(function(li) {
                var detail = li.querySelector('.notif-detail');
                var toggle = li.querySelector('.notif-toggle');

                if (!detail || !toggle) return;

                // If the element's scrollHeight is greater than its clientHeight, it's truncated
                if (detail.scrollHeight > detail.clientHeight + 1) {
                    toggle.style.display = 'inline-block';
                }

                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    var expanded = detail.classList.toggle('expanded');
                    if (expanded) {
                        toggle.textContent = 'Sembunyikan';
                    } else {
                        toggle.textContent = 'Selengkapnya';
                        // scroll into view a bit so user sees collapsed content
                        li.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                });
            });
        })();
    </script>
</body>

</html>
