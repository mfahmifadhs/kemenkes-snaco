<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jabatan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_jabatan";
    protected $primaryKey = "id_jabatan";
    public $timestamps = false;

    protected $fillable = [
        'jabatan'
    ];
}
