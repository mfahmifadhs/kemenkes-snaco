<?php

namespace App\Models;

use App\Models\Satuan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Snackcorner extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "t_snc";
    protected $primaryKey   = "id_snc";
    public $timestamps      = false;

    protected $fillable = [
        'id_snc',
        'snc_kategori',
        'snc_nama',
        'snc_deskripsi',
        'snc_satuan',
        'snc_maksimal',
        'snc_harga',
        'snc_keterangan',
        'snc_foto',
        'snc_status'
    ];

    public function kategori()
    {
        return $this->belongsTo(SnackcornerKategori::class, 'snc_kategori');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'snc_satuan');
    }

    public function permintaan()
    {
        return $this->hasMany(UsulanSnc::class, 'snc_id');
    }

    public function pemakaian()
    {
        return $this->hasMany(KegiatanDetail::class, 'snc_id');
    }

    // =====================================================================
    //                          STOK BARANG
    // =====================================================================

    public function stokMasuk()
    {
        return $this->hasMany(StokDetail::class, 'snc_id');
    }

    public function stokKeluar()
    {
        return $this->hasMany(UsulanSnc::class, 'snc_id')->whereNot('status', 'false');
    }

    public function stok()
    {
        $totalMasuk = $this->stokMasuk()->sum('jumlah');

        $totalKeluar = $this->stokKeluar()->sum('jumlah_permintaan');

        return $totalMasuk - $totalKeluar;
    }


    public function stokPermintaan()
    {
        return $this->hasMany(UsulanSnc::class, 'snc_id')->where('t_snc_usulan.status', 'true')
            ->join('t_usulan', 'id_usulan', 'usulan_id')
            ->join('users', 'id', 'user_id')
            ->join('t_pegawai', 'id_pegawai', 'pegawai_id');
    }

    // =====================================================================
    //                          STOK BARANG UKER
    // =====================================================================

    public function stokMasukUker()
    {
        return $this->hasMany(UsulanSnc::class, 'snc_id')->where('t_snc_usulan.status', 'true')
            ->join('t_usulan', 'id_usulan', 'usulan_id')
            ->join('users', 'id', 'user_id')
            ->join('t_pegawai', 'id_pegawai', 'pegawai_id')
            ->where('uker_id', Auth::user()->pegawai->uker_id);
    }

    public function stokKeluarUker()
    {
        return $this->hasMany(KegiatanDetail::class, 'snc_id')
            ->join('t_kegiatan', 'id_kegiatan', 'kegiatan_id')
            ->join('users', 'id', 'user_id')
            ->join('t_pegawai', 'id_pegawai', 'pegawai_id')
            ->where('t_kegiatan_detail.status', 'true')
            ->where('uker_id', Auth::user()->pegawai->uker_id);
    }

    public function stokUker($ukerId)
    {
        $dataMasuk  = $this->stokMasukUker();
        $dataKeluar = $this->stokKeluarUker();

        if ($ukerId) {
            $totalMasuk  = $dataMasuk->where('uker_id', $ukerId)->sum('jumlah_permintaan');
            $totalKeluar = $dataKeluar->where('uker_id', $ukerId)->sum('jumlah');
        } else {
            $totalMasuk  = $dataMasuk->sum('jumlah_permintaan');
            $totalKeluar = $dataKeluar->sum('jumlah');
        }

        return $totalMasuk - $totalKeluar;
    }
}
