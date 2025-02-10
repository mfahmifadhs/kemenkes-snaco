<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_pegawai";
    protected $primaryKey = "id_pegawai";
    public $timestamps = false;

    protected $fillable = [
        'uker_id',
        'nip',
        'nama_pegawai',
        'jabatan_id',
        'timker_id',
        'status'
    ];

    public function uker() {
        return $this->belongsTo(UnitKerja::class, 'uker_id');
    }

    public function jabatan() {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function timker() {
        return $this->belongsTo(TimKerja::class, 'timker_id');
    }

    public function user() {
        return $this->hasMany(User::class, 'pegawai_id');
    }
}
