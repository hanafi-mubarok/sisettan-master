<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MidiEmployee extends Model
{
    use SoftDeletes;
    
    protected $table = 'midi_employee';
    
    protected $fillable = [
        'id_jabatan',
        'id_branch',
        'nama_karyawan',
        'nik',
        'gender'
    ];
    
    protected $dates = ['deleted_at'];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'id_branch');
    }
}
