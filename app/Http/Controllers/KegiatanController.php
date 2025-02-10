<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanDetail;
use App\Models\SnackcornerKategori;
use Illuminate\Http\Request;
use Auth;
use Str;
use Carbon\Carbon;

class KegiatanController extends Controller
{
    public function show()
    {
        $role = Auth::user()->role_id;
        $uker = Auth::user()->pegawai->uker_id;
        $data = Kegiatan::with('user.pegawai')->orderBy('tanggal_kegiatan', 'desc');

        if ($role == 4) {
            $kegiatan = $data->whereHas('user.pegawai', function ($query) use ($uker) {
                $query->where('uker_id', $uker);
            })->get();
        } else {
            $kegiatan = $data->get();
        }

        return view('pages.kegiatan.show', compact('kegiatan'));

    }

    public function item()
    {
        $role = Auth::user()->role_id;
        $uker = Auth::user()->pegawai->uker_id;
        $data = KegiatanDetail::join('t_kegiatan','id_kegiatan','kegiatan_id')->with('kegiatan.user.pegawai');

        if ($role == 4) {
            $barang = $data->whereHas('kegiatan.user.pegawai', function ($query) use ($uker) {
                $query->where('uker_id', $uker);
            })->count();
        } else {
            $barang = $data->count();
        }

        return view('pages.kegiatan.barang', compact('barang'));
    }

    public function itemSelect()
    {
        $uker = Auth::user()->pegawai->uker_id;
        $role = Auth::user()->role_id;

        $data     = KegiatanDetail::join('t_kegiatan','id_kegiatan','kegiatan_id')->with('kegiatan.user.pegawai');
        $no       = 1;
        $response = [];

        if ($role == 4) {
            $result = $data->whereHas('kegiatan.user.pegawai', function ($query) use ($uker) {
                $query->where('uker_id', $uker);
            })->get();
        } else {
            $result = $data->get();
        }

        foreach ($result as $row) {
            $aksi   = '';
            $status = '';

            if ($row->snc->snc_foto) {
                $foto = '<img src="' . asset('storage/file/foto_snaco/' . $row->snc->snc_foto) . '" class="img-fluid" alt="">';
            } else {
                $foto = '<img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid" alt="">';
            }


            $response[] = [
                'no'         => $no,
                'id'         => $row->snc->id_snc,
                'kode'       => $row->kegiatan->kode_kegiatan,
                'foto'       => $foto,
                'fileFoto'   => $row->snc->snc_foto,
                'kategori'   => $row->snc->kategori->nama_kategori,
                'barang'     => $row->snc->snc_nama,
                'deskripsi'  => $row->snc->snc_deskripsi ?? '',
                'jumlah'     => $row->jumlah,
                'satuan'     => $row->snc->satuan->satuan,
                'maksimal'   => $row->snc->snc_maksimal,
                'keterangan' => $row->snc->snc_keterangan ?? '',
                'status'     => $status
            ];

            $no++;
        }

        return response()->json($response);
    }

    public function detail($id)
    {
        $kegiatan = Kegiatan::where('id_kegiatan', $id)->first();
        return view('pages.kegiatan.detail', compact('kegiatan'));
    }

    public function create()
    {
        return view('pages.kegiatan.create');
    }

    public function store(Request $request)
    {
        $id_kegiatan = Kegiatan::withTrashed()->count() + 1;

        $tambah = new Kegiatan();
        $tambah->id_kegiatan      = $id_kegiatan;
        $tambah->user_id          = $request->user_id;
        $tambah->kode_kegiatan    = Str::random(5);
        $tambah->tanggal_kegiatan = $request->tanggal;
        $tambah->nama_kegiatan    = $request->kegiatan;
        $tambah->jumlah_peserta   = $request->peserta;
        $tambah->keterangan       = $request->keterangan;
        $tambah->save();

        return redirect()->route('kegiatan.edit', $id_kegiatan)->with('success', 'Berhasil Membuat Kegiatan');
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::where('id_kegiatan', $id)->first();
        $kategori = SnackcornerKategori::get();
        return view('pages.kegiatan.edit', compact('kegiatan', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        Kegiatan::where('id_kegiatan', $id)->update([
            'tanggal_kegiatan' => $request->tanggal,
            'nama_kegiatan'    => $request->kegiatan,
            'jumlah_peserta'   => $request->peserta,
            'keterangan'       => $request->keterangan,
        ]);

        $detail = KegiatanDetail::where('kegiatan_id', $id)->where('status', 'false')->get();
        foreach ($detail as $row) {
            KegiatanDetail::where('id_detail', $row->id_detail)->update([
                'status' => 'true'
            ]);
        }

        return redirect()->route('kegiatan.edit', $id)->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function delete($id)
    {
        KegiatanDetail::where('kegiatan_id', $id)->delete();
        Kegiatan::where('id_kegiatan', $id)->delete();
        return redirect()->back()->with('success', 'Berhasil Menghapus');
    }

    public function itemStore(Request $request)
    {
        $user = Auth::user()->id;
        $snc  = KegiatanDetail::where('snc_id', $request->id_snc)->where('kegiatan_id', $request->kegiatan_id)->first();
        $qty  = (int)str_replace('.', '', $request->jumlah);

        if ($snc) {
            $total = $snc->jumlah + $qty;
            KegiatanDetail::where('id_detail', $snc->id_detail)->update([
                'jumlah' => $total
            ]);
        } else {
            $id_detail = KegiatanDetail::withTrashed()->count() + 1;
            $tambah    = new KegiatanDetail();
            $tambah->id_detail    = $id_detail;
            $tambah->kegiatan_id  = $request->kegiatan_id;
            $tambah->snc_id       = $request->id_snc;
            $tambah->jumlah       = $qty;
            $tambah->created_at   = Carbon::now();
            $tambah->save();
        }

        return redirect()->back()->with('success', 'Berhasil Menambah Barang');
    }

    public function itemUpdate(Request $request, $id)
    {
        $snc  = KegiatanDetail::where('id_detail' , $id)->first();
        $qty  = (int)str_replace('.', '', $request->jumlah);

        if ($snc) {
            KegiatanDetail::where('id_detail', $id)->update([
                'jumlah' => $qty
            ]);
        }


        return redirect()->back()->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function itemDelete(Request $request, $id)
    {
        KegiatanDetail::where('id_detail', $id)->delete();

        return redirect()->back()->with('success', 'Berhasil Menghapus');
    }
}
