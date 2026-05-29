<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kecamatan extends Model
{
    use SoftDeletes;
    // table renamed to 'branch'
    protected $table = 'branch';
    protected $fillable = ['branch'];
    protected $dates = ['deleted_at'];
}
