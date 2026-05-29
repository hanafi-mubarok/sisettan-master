<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penawaran extends Model
{
    use SoftDeletes;
    protected $table = 'penawarans';
    protected $fillable = [
        'id_penawaran',
        'id_user',
        'name',
        'idfk_barang',
        'aset_id',
        'nilai_penawaran',
        'keterangan',
        'gugur',
        'status_pelelang_id',
    ];
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function daftar()
    {
        return $this->belongsTo(Daftar::class);
    }

    public function tkd()
    {
        return $this->belongsTo(Tkd::class, 'idfk_barang');
    }

    public function getIdfkDaftarAttribute()
    {
        return $this->attributes['id_user'] ?? null;
    }

    public function getIdDaftarAttribute()
    {
        return $this->attributes['name'] ?? null;
    }

    public function getIdfkTkdAttribute()
    {
        return $this->attributes['idfk_barang'] ?? null;
    }

    public function getIdTkdAttribute()
    {
        return $this->attributes['aset_id'] ?? null;
    }

    public function legacyTkd()
    {
        return $this->belongsTo(Tkd::class, 'idfk_barang');
    }
}
