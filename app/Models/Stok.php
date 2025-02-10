<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stok extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "t_stok";
    protected $primaryKey   = "id_stok";
    public $timestamps      = false;

    protected $fillable = [
        'id_stok',
        'kode_stok',
        'tanggal_masuk',
        'no_kwitansi',
        'total_harga',
        'keterangan'
    ];

    public function detail() {
        return $this->hasMany(StokDetail::class, 'stok_id');
    }
}
