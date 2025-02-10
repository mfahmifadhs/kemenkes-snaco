<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanSnc extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "t_snc_usulan";
    protected $primaryKey   = "id_usulan_snc";
    public $timestamps      = false;

    protected $fillable = [
        'usulan_id',
        'snc_id',
        'satuan_id',
        'jumlah_permintaan',
        'jumlah_disetujui',
        'jumlah_penyerahan',
        'status',
        'harga_permintaan',
        'harga_penyerahan',
        'keterangan_permintaan'
    ];

    public function usulan() {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }

    public function snc() {
        return $this->belongsTo(Snackcorner::class, 'snc_id');
    }
}
