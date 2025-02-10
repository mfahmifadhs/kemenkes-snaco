<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kegiatan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_kegiatan";
    protected $primaryKey = "id_kegiatan";
    public $timestamps = false;

    protected $fillable = [
        'id_kegiatan',
        'user_id',
        'kode_kegiatan',
        'tanggal_kegiatan',
        'nama_kegiatan',
        'jumlah_peserta',
        'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detail()
    {
        return $this->hasMany(KegiatanDetail::class, 'kegiatan_id');
    }
}
