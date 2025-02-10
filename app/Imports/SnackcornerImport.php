<?php

namespace App\Imports;

use App\Models\OLDAT\Barang;
use App\Models\OLDAT\KategoriBarang;
use App\Models\OLDAT\KondisiBarang;
use App\Models\Satuan;
use App\Models\Snackcorner;
use App\Models\SnackcornerKategori;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SnackcornerImport implements ToModel, WithStartRow
{

    // [0] = No, [1] = Kategori ID, [2] = Nama, [3] = Deskripsi, [4] = Maksimal
    // [5] = Harga, [6] = Stok, [7] = Satuan, [8] = Status, [9] = ID
    public function model(array $row)
    {
        if (isset($row[10]) || isset($row[8]) || isset($row[7])) {
            $id     = Snackcorner::where('id_snc', $row[10])->first();
            $satuan = Satuan::select('id_satuan')->where('satuan', 'like', '%' .$row[8]. '%')->first();
            $status   = strtolower($row[9]) == 'tersedia' ? 'true' : 'false';
        }  else {
            $id     = null;
            $satuan = null;
            $status = null;
        }

        $kategori = SnackcornerKategori::where('nama_kategori', 'like', '%' .$row[2]. '%')->first();
        $satuan   = $satuan ? $satuan->id_satuan : null;
        $id       = $id ? $id->id_snc : null;
        $harga    = (strpos($row[6], 'Rp') !== false) ? intval(str_replace([',', 'Rp'], '', $row[6])) : intval($row[6]);

        if ($id) {
            Snackcorner::where('id_snc', $id)->update([
                'snc_kategori'   => $kategori->id_kategori,
                'snc_nama'       => $row[3],
                'snc_deskripsi'  => $row[4],
                'snc_satuan'     => $satuan,
                'snc_maksimal'   => $row[5],
                'snc_harga'      => $harga,
                'snc_keterangan' => null,
                'snc_foto'       => $row[1],
                'snc_status'     => $status
            ]);
        } else {
            $id_snc = Snackcorner::withTrashed()->count() + 1;
            $tambah = new Snackcorner();
            $tambah->id_snc         = $id_snc;
            $tambah->snc_kategori   = $kategori->id_kategori;
            $tambah->snc_nama       = $row[3];
            $tambah->snc_deskripsi  = $row[4];
            $tambah->snc_satuan     = $satuan;
            $tambah->snc_maksimal   = $row[5];
            $tambah->snc_harga      = $row[6];
            $tambah->snc_keterangan = null;
            $tambah->snc_foto       = $row[2];
            $tambah->snc_status     = $status;
            $tambah->created_at     = Carbon::now();
            $tambah->save();
        }
    }

    public function startRow(): int
    {
        return 3;
    }
}
