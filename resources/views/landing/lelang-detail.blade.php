<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang Lelang</title>
    <link rel="stylesheet" href="{{ asset('assetLanding/css/bootstrap-min.css') }}">
    <style>
        body {
            background: #f2f5f8;
            color: #132131;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .detail-wrap {
            max-width: 960px;
            margin: 40px auto;
            padding: 0 15px;
        }

        .detail-card {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 14px 30px rgba(0, 31, 64, 0.12);
            overflow: hidden;
        }

        .detail-image {
            width: 100%;
            height: 380px;
            object-fit: cover;
            display: block;
        }

        .detail-body {
            padding: 28px;
        }

        .detail-title {
            margin: 0 0 6px;
            font-size: 30px;
            font-weight: 700;
            line-height: 1.2;
        }

        .detail-subtitle {
            color: #5d6a78;
            margin-bottom: 24px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .detail-item {
            background: #f6f9fc;
            border-radius: 10px;
            padding: 12px 14px;
        }

        .detail-label {
            margin: 0;
            font-size: 12px;
            color: #5d6a78;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .detail-value {
            margin: 4px 0 0;
            font-size: 16px;
            font-weight: 600;
        }

        .detail-description {
            margin-top: 16px;
            padding: 14px;
            border-radius: 10px;
            background: #f6f9fc;
            line-height: 1.6;
        }

        .detail-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        @media (max-width: 767px) {
            .detail-image {
                height: 230px;
            }

            .detail-title {
                font-size: 24px;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="detail-wrap">
        <div class="detail-card">
            <img src="{{ $detail->foto_url }}" alt="{{ $detail->nama_tampil }}" class="detail-image">
            <div class="detail-body">
                <h1 class="detail-title">{{ $detail->nama_tampil }}</h1>
                <p class="detail-subtitle">{{ $detail->lokasi ?? '-' }}</p>

                <div class="detail-grid">
                    <div class="detail-item">
                        <p class="detail-label">Kategori</p>
                        <p class="detail-value">{{ $detail->kategori ?? '-' }}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Merek / Jenis</p>
                        <p class="detail-value">{{ $detail->merk ?? '-' }}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Nilai Awal</p>
                        <p class="detail-value">{{ $detail->harga_dasar_rupiah }}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Jadwal Penawaran</p>
                        <p class="detail-value">{{ $detail->tgl_start_penawaran_label }}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Luas / Kelipatan</p>
                        <p class="detail-value">{{ $detail->kelipatan ? number_format($detail->kelipatan, 0, ',', '.') . ' m2' : '-' }}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Kondisi</p>
                        <p class="detail-value">{{ $detail->kondisi ?? '-' }}</p>
                    </div>
                </div>

                <div class="detail-description">
                    <strong>Keterangan:</strong>
                    <div>{{ $detail->keterangan ?: '-' }}</div>
                </div>

                <div class="detail-actions">
                    <a href="{{ route('landing-page') }}" class="btn btn-default">Kembali ke Landing</a>
                    <a href="{{ route('penawaran.index') }}" class="btn btn-primary">Masuk ke Halaman Penawaran</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
