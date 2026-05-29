<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil Saya</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6C/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard-user.css') }}">
    @stack('customStyle')
    <style>
        .profile-page .profile-box {
            background: #fff;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .profile-page .profile-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .profile-page .profile-field {
            background: #f7f9fb;
            border-radius: 14px;
            padding: 14px 16px;
        }

        .profile-page .profile-field label {
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6c757d;
            margin-bottom: 6px;
        }

        .profile-page .profile-field strong {
            color: #1f2d3d;
            word-break: break-word;
        }

        .profile-page .profile-avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #18354d, #2f6c92);
            color: #fff;
            font-size: 36px;
            font-weight: 700;
            margin: 0 auto 18px;
        }

        @media (max-width: 768px) {
            .profile-page .profile-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="midi-wrapper profile-page">
        <div class="midi-shell">
            @include('dashboard.partials.sidebar')

            <main class="midi-main">
                <section class="midi-content">
                    <div class="profile-box">
                        <div class="profile-avatar">
                            {{ strtoupper(substr((string) ($user->name ?? 'U'), 0, 1)) }}
                        </div>

                        <h1 class="mb-1">Profil Saya</h1>
                        <p class="text-muted mb-4">Informasi akun dan data diri dari tabel users.</p>

                        <div class="profile-grid">
                            <div class="profile-field"><label>ID</label><strong>{{ $user->id }}</strong></div>
                            <div class="profile-field"><label>NIK</label><strong>{{ $user->nik ?? '-' }}</strong></div>
                            <div class="profile-field"><label>Username</label><strong>{{ $user->username ?? '-' }}</strong></div>
                            <div class="profile-field"><label>Nama</label><strong>{{ $user->name ?? '-' }}</strong></div>
                            <div class="profile-field"><label>Email</label><strong>{{ $user->email ?? '-' }}</strong></div>
                            <div class="profile-field"><label>No. HP</label><strong>{{ $user->phone ?? '-' }}</strong></div>
                            <div class="profile-field"><label>Provinsi</label><strong>{{ $user->province ?? '-' }}</strong></div>
                            <div class="profile-field"><label>Kota</label><strong>{{ $user->city ?? '-' }}</strong></div>
                            <div class="profile-field"><label>Alamat</label><strong>{{ $user->address ?? '-' }}</strong></div>
                            <div class="profile-field"><label>Keterangan</label><strong>{{ $user->keterangan ?? '-' }}</strong></div>
                            <div class="profile-field"><label>Karyawan</label><strong>{{ $user->is_karyawan ? 'Ya' : 'Tidak' }}</strong></div>
                            <div class="profile-field"><label>Role</label><strong>{{ $user->role_name ?? '-' }}</strong></div>
                            <div class="profile-field"><label>Status Verifikasi</label><strong>{{ $user->isverified ? 'Terverifikasi' : 'Belum Verifikasi' }}</strong></div>
                            <div class="profile-field"><label>Blacklist</label><strong>{{ $user->is_blacklisted ? 'Ya' : 'Tidak' }}</strong></div>
                            <div class="profile-field"><label>Email Terverifikasi</label><strong>{{ optional($user->email_verified_at)->format('d M Y H:i') ?? '-' }}</strong></div>
                            <div class="profile-field"><label>Diperbarui</label><strong>{{ optional($user->updated_at)->format('d M Y H:i') ?? '-' }}</strong></div>
                            <div class="profile-field"><label>Selfie</label><strong>@if($user->selfie_ktp_path)<a href="{{ asset('storage/' . $user->selfie_ktp_path) }}" target="_blank" rel="noopener noreferrer">Lihat file</a>@else - @endif</strong></div>
                            <div class="profile-field"><label>KTP</label><strong>@if($user->kartu_keluarga_path)<a href="{{ asset('storage/' . $user->kartu_keluarga_path) }}" target="_blank" rel="noopener noreferrer">Lihat file</a>@else - @endif</strong></div>
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
</body>

</html>