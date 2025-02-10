<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SnackcornerKeranjang extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "t_snc_keranjang";
    protected $primaryKey   = "id_keranjang";
    public $timestamps      = false;

    protected $fillable = [
        'id_keranjang',
        'user_id',
        'snc_id',
        'kuantitas',
        'status_id'
    ];

    public function snc() {
        return $this->belongsTo(Snackcorner::class, 'snc_id');
    }
}
