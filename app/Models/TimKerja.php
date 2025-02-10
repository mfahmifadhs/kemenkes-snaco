<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimKerja extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_tim_kerja";
    protected $primaryKey = "id_tim_kerja";
    public $timestamps = false;

    protected $fillable = [
        'id_tim_kerja',
        'uker_id',
        'tim_kerja'
    ];

    public function uker() {
        return $this->belongsTo(UnitKerja::class, 'uker_id');
    }
}
