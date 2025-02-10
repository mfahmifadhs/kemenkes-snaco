<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StokDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "t_stok_detail";
    protected $primaryKey   = "id_detail";
    public $timestamps      = false;

    protected $fillable = [
        'id_detail',
        'stok_id',
        'snc_id',
        'jumlah'
    ];

    public function stok() {
        return $this->belongsTo(Stok::class, 'stok_id');
    }

    public function snc() {
        return $this->belongsTo(Snackcorner::class, 'snc_id');
    }
}
