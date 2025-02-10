<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitKerja extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_unit_kerja";
    protected $primaryKey = "id_unit_kerja";
    public $timestamps = false;

    protected $fillable = [
        'id_unit_kerja',
        'utama_id',
        'unit_kerja',
        'kode_surat',
        'singkatan'
    ];

    public function utama()
    {
        return $this->belongsTo(UnitUtama::class, 'utama_id');
    }

    public function usulan($id)
    {
        $uker = Usulan::join('users', 'id', 'user_id')
            ->join('t_pegawai', 'id_pegawai', 'pegawai_id')
            ->where('uker_id', $id)
            ->groupBy('uker_id')
            ->count('id_usulan');

        return $uker;
        // return $this->hasManyThrough(
        //     Usulan::class,
        //     User::class,
        //     'pegawai_id',
        //     'user_id',
        //     'id_unit_kerja',
        //     'id'
        // );
    }
}
