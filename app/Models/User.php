<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    protected $table = "users";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        'role_id',
        'pegawai_id',
        'deskripsi',
        'username',
        'password',
        'password_text',
        'email',
        'status'
    ];

    public function Role() {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function pegawai() {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function keranjang() {
        return $this->hasMany(SnackcornerKeranjang::class, 'user_id');
    }

    public function kegiatan() {
        return $this->hasMany(Kegiatan::class, 'user_id');
    }
}
