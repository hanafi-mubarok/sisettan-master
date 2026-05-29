<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusBarang extends Model
{
    protected $table = 'status_barang';

    protected $fillable = [
        'status_name',
    ];
}
