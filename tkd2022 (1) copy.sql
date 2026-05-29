--
-- Dumping data untuk tabel `daerahs`
--

INSERT INTO `daerahs` (`id`, `id_kelurahan`, `id_kecamatan`, `noba`, `periode`, `thn_sts`, `tanggal_lelang`, `created_at`, `updated_at`) VALUES
(1, 26, 3, '56', '1 Januari 2023 s/d 31 Desember 2024', 4, '2022-12-27', '2023-08-15 05:47:44', '2023-08-15 05:47:44'),
(2, 1, 1, '25', '22 OKTOBER 2022 S/D 21 OKTOBER 2023', 4, '2022-09-26', '2023-08-15 05:47:44', '2023-08-15 05:47:44'),
(3, 47, 2, '6', '1 NOPEMBER 2022 s.d 31 OKTOBER 2023', NULL, '2022-09-07', '2023-08-15 05:47:44', '2023-08-15 05:47:44'),
(4, 56, 2, NULL, NULL, NULL, NULL, '2023-08-15 05:47:44', '2023-08-15 05:47:44'),
(5, 3, 1, '26', '1 JANUARI 2023 S/D 31 DESEMBER 2023', 4, '2022-09-27', '2023-08-15 05:47:44', '2023-08-15 05:47:44'),
(6, 29, 3, '58', '1 Januari 2023 s/d 31 Desember 2024', 4, '2022-12-27', '2023-08-15 05:47:44', '2023-08-15 05:47:44'),
(7, 19, 2, '1', '1 NOPEMBER 2022 s.d 31 OKTOBER 2023', 4, '2022-09-05', '2023-08-15 05:47:44', '2023-08-15 05:47:44'),
(8, 14, 2, '3', '1 NOPEMBER 2022 s.d 31 OKTOBER 2023', NULL, '2022-09-06', '2023-08-15 05:47:44', '2023-08-15 05:47:44'),
(9, 9, 2, '15', '1 JANUARI 2023 s.d 31 DESEMBER 2023', NULL, '2022-09-14', '2023-08-15 05:47:44', '2023-08-15 05:47:44'),
(10, 13, 2, '10', '1 JANUARI 2023 s.d 31 DESEMBER 2023', NULL, '2022-09-12', '2023-08-15 05:47:44', '2023-08-15 05:47:44'),
(11, 20, 2, '2', '1 NOPEMBER 2022 s.d 31 OKTOBER 2023', NULL, '2022-09-05', '2023-08-15 05:47:44', '2023-08-15 05:47:44'),
(12, 12, 2, '11', '1 JANUARI 2023 s.d 31 DESEMBER 2023', NULL, '2022-09-12', '2023-08-15 05:47:44', '2023-08-15 05:47:44'),
(13, 18, 2, '12', '1 JANUARI 2023 s.d 31 DESEMBER 2023', NULL, '2022-09-13', '2023-08-15 05:47:44', '2023-08-15 05:47:44'),
(14, 16, 2, '8', '1 JANUARI 2023 s.d 31 DESEMBER 2023', NULL, '2022-09-08', '2023-08-15 05:47:44', '2023-08-15 05:47:44'),
(15, 17, 2, '7', '1 NOPEMBER 2022 s.d 31 OKTOBER 2023', NULL, '2022-09-08', '2023-08-15 05:47:44', '2023-08-15 05:47:44'),
(16, /* large SQL query (5,3 KiB), snipped at 2.000 characters */
/* SQL Error (1452): Cannot add or update a child row: a foreign key constraint fails (`dimas_lelang`.`daerahs`, CONSTRAINT `daerahs_id_kelurahan_foreign` FOREIGN KEY (`id_kelurahan`) REFERENCES `kelurahans` (`id`) ON DELETE RESTRICT) */
/* Affected rows: 5  Found rows: 0  Warnings: 7  Duration for 10 of 118 queries: 0,016 sec. */