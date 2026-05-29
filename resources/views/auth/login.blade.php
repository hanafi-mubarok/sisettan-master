<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Peserta</title>
    <link rel="stylesheet" type="text/css" href="assetsLogin/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assetsLogin/css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="assetsLogin/css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="assetsLogin/css/iofrm-theme6.css">
</head>

<body class="midi-login-page">
    <header class="midi-topbar">
        <a href="{{ route('landing-page') }}" class="midi-brand">
            <img src="{{ asset('images/logo_midi.png') }}" alt="Logo Midi">
            <span>PT Midi Utama Indonesia Tbk</span>
        </a>

        <nav class="midi-menu">
            <a href="{{ route('landing-page') }}">Beranda</a>
            <a href="#">Jadwal Lelang</a>>
        </nav>
    </header>

    <main class="midi-content">
        <section class="midi-login-card">
            <h1 class="midi-title">Login</h1>
            <p class="midi-subtitle">Portal Lelang Aset PT. Midi Utama Indonesia Tbk</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="midi-field">
                    <label for="email">Email</label>
                    <input id="email" class="@error('email') is-invalid @enderror" type="email" name="email"
                        value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')
                    <div class="midi-error-box">{{ $message }}</div>
                @enderror

                <div class="midi-field">
                    <label for="password">Kata Sandi</label>
                    <input id="password" type="password" name="password" required>
                </div>
                @error('password')
                    <div class="midi-error-box">{{ $message }}</div>
                @enderror

                <button id="submit" type="submit" class="midi-btn-login">Masuk</button>

                <div class="midi-links">
                    @if (Route::has('password.request'))
                        <div><a href="{{ route('password.request') }}">Lupa kata sandi?</a></div>
                    @endif
                    <div>
                        Belum punya akun?
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Daftar sekarang</a>
                        @else
                            <a href="#">Daftar sekarang</a>
                        @endif
                    </div>
                </div>
            </form>
        </section>
    </main>

    <footer class="midi-footer">
        &copy; {{ date('Y') }} PT. Midi Utama Indonesia Tbk &nbsp;&nbsp; Kontak &nbsp;&nbsp; Kebijakan Privasi
    </footer>

    <script src="assetsLogin/js/jquery.min.js"></script>
    <script src="assetsLogin/js/popper.min.js"></script>
    <script src="assetsLogin/js/bootstrap.min.js"></script>
    <script src="assetsLogin/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('registration_success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('registration_success')),
                confirmButtonText: 'OK'
            });
        </script>
    @endif

</body>

</html>
