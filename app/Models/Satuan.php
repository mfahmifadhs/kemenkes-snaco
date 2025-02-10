<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Satuan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "t_satuan";
    protected $primaryKey   = "id_satuan";
    public $timestamps      = false;

    protected $fillable = [
        'id_satuan',
        'satuan',
        'deskripsi'
    ];
}
