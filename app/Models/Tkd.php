<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tkd extends Model
{
    use SoftDeletes;
    protected $table = 'barang_yang_dilelang';
    protected $fillable = [
        'id_tkd',
        'aset_id',
        'id_branch',
        'id_kelurahan',
        'nama_barang',
        'kondisi',
        'kategori',
        'merk',
        'bidang',
        'letak',
        'lokasi',
        'bukti',
        'harga_dasar',
        'kelipatan',
        'luas',
        'tahun',
        'status',
        'longitude',
        'latitude',
        'keterangan',
        'foto',
        'nop',
        'tgl_start_penawaran',
        'tgl_akhir_penawaran',
        'uploaded_by'
    ];
    protected $dates = ['deleted_at'];
}
