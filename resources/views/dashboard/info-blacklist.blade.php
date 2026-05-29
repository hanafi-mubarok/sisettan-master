<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Info Blacklist - Dashboard Peserta</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard-user.css') }}">
</head>

<body>
    <div class="midi-wrapper">
        <div class="midi-shell">
            @include('dashboard.partials.sidebar')

            <main class="midi-main">
                <section class="midi-content">
                    <h1>Selamat Datang, {{ auth()->user()->name }}</h1>

                    <div class="midi-main-grid">
                        <div class="midi-main-left" style="grid-column: 1 / -1;">
                            <div class="midi-card-box">
                                <h3 class="midi-card-title">
                                    <i class="far fa-ban" style="margin-right: 8px;"></i> Daftar Peserta Blacklist
                                </h3>
                                <br>
                                @if ($blacklistedUsers->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" style="margin-bottom: 0;">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th style="width: 5%;">No</th>
                                                    <th style="width: 20%;">Nama</th>
                                                    <th style="width: 20%;">Username</th>
                                                    <th style="width: 25%;">Email</th>
                                                    <th style="width: 30%;">Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($blacklistedUsers as $index => $user)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $user->name }}</td>
                                                        <td>
                                                            <span class="badge badge-danger">{{ $user->username_tersensor }}</span>
                                                        </td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>
                                                            @if (!empty($user->keterangan))
                                                                <small class="text-muted">{{ $user->keterangan }}</small>
                                                            @else
                                                                <small class="text-muted text-italic">-</small>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="midi-empty" style="padding: 40px; text-align: center;">
                                        <i class="far fa-smile" style="font-size: 48px; color: #ccc; margin-bottom: 16px; display: block;"></i>
                                        <p>Tidak ada peserta yang di-blacklist saat ini.</p>
                                    </div>
                                @endif
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
</body>

</html>
