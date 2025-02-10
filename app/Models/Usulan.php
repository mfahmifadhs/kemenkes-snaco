<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usulan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_usulan";
    protected $primaryKey = "id_usulan";
    public $timestamps = false;

    protected $fillable = [
        'id_usulan',
        'user_id',
        'verif_id',
        'form_id',
        'tanggal_usulan',
        'nomor_usulan',
        'keterangan',
        'keterangan_tolak',
        'nama_penerima',
        'status_persetujuan',
        'status_proses',
        'otp_1',
        'otp_2',
        'otp_3',
        'otp_4',
        'otp_5'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verif() {
        return $this->belongsTo(Pegawai::class, 'verif_id');
    }

    public function form() {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function usulanSnc() {
        return $this->hasMany(UsulanSnc::class, 'usulan_id');
    }
}
