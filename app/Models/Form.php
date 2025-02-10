<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_form";
    protected $primaryKey = "id_form";
    public $timestamps = false;

    protected $fillable = [
        'id_form',
        'kategori',
        'klasifikasi',
        'nama_form'
    ];
}
