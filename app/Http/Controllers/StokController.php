<?php

namespace App\Http\Controllers;

use App\Models\StokDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class StokController extends Controller
{
    public function itemShow()
    {
        $role   = Auth::user()->role_id;
        $uker   = Auth::user()->pegawai->uker_id;
        $barang = StokDetail::count();

        return view('pages.snackcorner.stok.barang', compact('barang'));
    }

    public function itemSelectAll()
    {
        $role   = Auth::user()->role_id;
        $uker   = Auth::user()->pegawai->uker_id;
        $result = StokDetail::with('snc')->get();

        $no       = 1;
        $response = [];

        foreach ($result as $row) {
            $aksi   = '';
            $status = '';

            if ($row->snc->snc_foto) {
                $foto = '<img src="' . asset('dist/img/foto_snaco/' . $row->snc->snc_foto) . '" class="img-fluid" alt="">';
            } else {
                $foto = '<img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid" alt="">';
            }


            $response[] = [
                'no'         => $no,
                'id'         => $row->snc->id_snc,
                'kode'       => $row->stok->kode_stok,
                'foto'       => $foto,
                'fileFoto'   => $row->snc->snc_foto,
                'kategori'   => $row->snc->kategori->nama_kategori,
                'barang'     => $row->snc->snc_nama,
                'deskripsi'  => $row->snc->snc_deskripsi ?? '',
                'harga'      => 'Rp' . number_format($row->snc->snc_harga, 0, '.'),
                'satuan'     => $row->snc->satuan->satuan,
                'maksimal'   => $row->snc->snc_maksimal,
                'jumlah'     => $row->jumlah,
                'keterangan' => $row->snc->snc_keterangan ?? ''
            ];

            $no++;
        }

        return response()->json($response);
    }
}
