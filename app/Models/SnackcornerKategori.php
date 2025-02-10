<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SnackcornerKategori extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "t_snc_kategori";
    protected $primaryKey   = "id_kategori";
    public $timestamps      = false;

    protected $fillable = [
        'id_kategori',
        'nama_kategori',
        'deskripsi',
        'icon',
        'status'
    ];

    public function snc()
    {
        return $this->hasMany(Snackcorner::class, 'snc_kategori');
    }

    public function stok($id = null)
    {
        if (!$id) {
            return 0; // Jika tidak ada ID, kembalikan 0 untuk mencegah error
        }

        $totalMasuk  = StokDetail::join('t_snc', 'id_snc', 'snc_id')
            ->join('t_snc_kategori', 'id_kategori', 'snc_kategori')
            ->where('snc_kategori', $id)
            ->groupBy('snc_kategori')
            ->sum('jumlah');

        $totalKeluar = UsulanSnc::join('t_snc', 'id_snc', 'snc_id')
            ->join('t_snc_kategori', 'id_kategori', 'snc_kategori')
            ->where('snc_kategori', $id)
            ->groupBy('snc_kategori')
            ->sum('jumlah_permintaan');

        return $totalMasuk - $totalKeluar;
    }
}
