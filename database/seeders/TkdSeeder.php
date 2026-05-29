<?php

namespace Database\Seeders;

use App\Models\Tkd;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TkdSeeder extends Seeder
{

    public function run()
    {
        Tkd::insert(
            [
                [
                    'aset_id' => '13S1',
                    'id_branch' => '13',
                    'kategori' => '1',
                    'merk' => 'Kel Jamsaren',
                    'lokasi' => 'SHP 58',
                    'harga_dasar' => '1645000',
                    'kelipatan' => '1225',
                    'keterangan' => null,
                    'status' => null,
                ],
                [
                    'aset_id' => '13S10',
                    'id_branch' => '13',
                    'kategori' => '2',
                    'merk' => 'Kel Jamsaren',
                    'lokasi' => 'SHP 52',
                    'harga_dasar' => '2027300',
                    'kelipatan' => '1514',
                    'keterangan' => null,
                    'status' => null,
                ],
                [
                    'aset_id' => null,
                    'id_branch' => '13',
                    'kategori' => '1',
                    'merk' => 'Kel Jamsaren',
                    'lokasi' => 'SHP 51',
                    'harga_dasar' => '2027533',
                    'kelipatan' => '1514',
                    'keterangan' => null,
                    'status' => null,
                ],
                [
                    'aset_id' => null,
                    'id_branch' => '13',
                    'kategori' => '2',
                    'merk' => 'Kel Jamsaren',
                    'lokasi' => 'SHP 51',
                    'harga_dasar' => '2027533',
                    'kelipatan' => '1514',
                    'keterangan' => null,
                    'status' => null,
                ],
                [
                    'aset_id' => null,
                    'id_branch' => '13',
                    'kategori' => '3',
                    'merk' => 'Kel Jamsaren',
                    'lokasi' => 'SHP 51',
                    'harga_dasar' => '2027533',
                    'kelipatan' => '1514',
                    'keterangan' => null,
                    'status' => null,
                ],
                [
                    'aset_id' => null,
                    'id_branch' => '14',
                    'kategori' => '1',
                    'merk' => 'Tempurejo',
                    'lokasi' => 'SHP 7',
                    'harga_dasar' => '3767000',
                    'kelipatan' => '3060',
                    'keterangan' => 'Lor omah Kwangkalan',
                    'status' => null,
                ],
                [
                    'aset_id' => null,
                    'id_branch' => '14',
                    'kategori' => '1',
                    'merk' => 'Blabak',
                    'lokasi' => 'SHP 6',
                    'harga_dasar' => '1839500',
                    'kelipatan' => '1485',
                    'keterangan' => null,
                    'status' => null,
                ],
                [
                    'aset_id' => null,
                    'id_branch' => '14',
                    'kategori' => '1',
                    'merk' => 'Blabak',
                    'lokasi' => 'SHP 7',
                    'harga_dasar' => '5004500',
                    'kelipatan' => '3935',
                    'keterangan' => 'Lor omah Pagut',
                    'status' => null,
                ],
                [
                    'aset_id' => null,
                    'id_branch' => '15',
                    'kategori' => '1',
                    'merk' => 'Kidul Omah',
                    'lokasi' => 'SHP 6',
                    'harga_dasar' => '2620500',
                    'kelipatan' => '1940',
                    'keterangan' => 'Jl. Raya Bawang - Betet',
                    'status' => null,
                ],
                [
                    'aset_id' => null,
                    'id_branch' => '15',
                    'kategori' => '1',
                    'merk' => 'Kel. Tinalan',
                    'lokasi' => 'Seb. SHP 13 dan 14',
                    'harga_dasar' => '3750000',
                    'kelipatan' => '2800',
                    'keterangan' => 'Sebagian sisa lapangan',
                    'status' => null,
                ],
                [
                    'aset_id' => null,
                    'id_branch' => '15',
                    'kategori' => '1',
                    'merk' => 'Belakang Gedung',
                    'lokasi' => 'Seb. SHP 16',
                    'harga_dasar' => '18000000',
                    'kelipatan' => '8570',
                    'keterangan' => null,
                    'status' => null,
                ],
                [
                    'aset_id' => null,
                    'id_branch' => '16',
                    'kategori' => '1',
                    'merk' => 'Jambu/pagu',
                    'lokasi' => 'SHP 3',
                    'harga_dasar' => '7975000',
                    'kelipatan' => '5530',
                    'keterangan' => null,
                    'status' => null,
                ],
                [
                    'aset_id' => null,
                    'id_branch' => '16',
                    'kategori' => '1',
                    'merk' => 'Gringging',
                    'lokasi' => 'SHP 1',
                    'harga_dasar' => '27905000',
                    'kelipatan' => '19015',
                    'keterangan' => null,
                    'status' => null,
                ],
                [
                    'aset_id' => null,
                    'id_branch' => '16',
                    'kategori' => '1',
                    'merk' => 'Bawang',
                    'lokasi' => 'SHP 28',
                    'harga_dasar' => '5642850',
                    'kelipatan' => '3880',
                    'keterangan' => 'Eks Mudin Jaenuri (sebagian tanah tidak produktif)',
                    'status' => null,
                ],
            ]
        );
    }
}

