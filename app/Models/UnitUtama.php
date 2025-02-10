<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitUtama extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_unit_utama";
    protected $primaryKey = "id_unit_utama";
    public $timestamps = false;

    protected $fillable = [
        'id_unit_utama',
        'unit_utama'
    ];

    public function uker() {
        return $this->hasMany(UnitKerja::class, 'utama_id');
    }
}
