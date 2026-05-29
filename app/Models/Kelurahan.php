<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelurahan extends Model
{
    use SoftDeletes;
    // table renamed to 'branch' by migration
    protected $table = 'branch';
    protected $fillable = [
        'branch', 'id_branch'
    ];
    protected $dates = ['deleted_at'];

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }
}
