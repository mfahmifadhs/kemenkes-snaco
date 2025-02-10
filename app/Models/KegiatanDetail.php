<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KegiatanDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_kegiatan_detail";
    protected $primaryKey = "id_detail";
    public $timestamps = false;

    protected $fillable = [
        'id_detail',
        'kegiatan_id',
        'snc_id',
        'jumlah',
        'timker_id',
        'status'
    ];

    public function kegiatan() {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function snc() {
        return $this->belongsTo(Snackcorner::class, 'snc_id');
    }
}
