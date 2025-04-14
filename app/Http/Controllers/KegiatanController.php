<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanDetail;
use App\Models\SnackcornerKategori;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Auth;
use Str;
use Carbon\Carbon;

class KegiatanController extends Controller
{
    public function show(Request $request)
    {
        $role  = Auth::user()->role_id;
        $data  = Kegiatan::with('user.pegawai')->orderBy('tanggal_kegiatan', 'desc');
        $uker  = $request->uker ?? null;
        $absen = $request->absen ?? null;
        $userUker = Auth::user()->pegawai->uker_id;
        $ukerList = UnitKerja::where('utama_id', '46593')->orderBy('unit_kerja', 'asc')->get();

        if ($role == 4) {
            $data = $data->whereHas('user.pegawai', function ($query) use ($userUker) {
                $query->where('uker_id', $userUker);
            })->count();
        } else {
            $data = $data->count();
        }

        return view('pages.kegiatan.show', compact('uker', 'absen', 'ukerList', 'data'));
    }

    public function select(Request $request)
    {
        $role  = Auth::user()->role_id;
        $uker  = $request->uker;
        $absen = $request->absen;

        $data     = Kegiatan::orderBy('id_kegiatan', 'asc')->orderBy('data_pendukung', 'desc');
        $no       = 1;
        $response = [];

        if ($role == 4) {
            $data = $data->where('user_id', Auth::user()->id);
        }

        if ($uker || $absen) {
            if ($uker) {
                $res = $data->whereHas('user.pegawai.uker', function ($query) use ($uker) {
                    $query->where('id_unit_kerja', $uker);
                });
            }

            if ($absen) {
                if ($absen == 'true') {
                    $res = $data->whereNotNull('data_pendukung');
                } else {
                    $res = $data->whereNull('data_pendukung');
                }
            }

            $result = $res->get();
        } else {
            $result = $data->get();
        }

        foreach ($result as $row) {
            $aksi   = '';
            $status = '';

            if ($role == 4) {
                $aksi .= '
                    <a href="'. route('kegiatan.detail', $row->id_kegiatan) .'" class="btn btn-default btn-xs bg-primary rounded border-dark">
                        <i class="fas fa-info-circle p-1" style="font-size: 12px;"></i>
                    </a>
                ';
            }

            $file ='
                <a href="'. route('kegiatan.lihat-pdf', $row->id_kegiatan) .'" class="btn btn-danger btn-xs" target="_blank">
                    <i class="fas fa-file-pdf"></i> <small>Absen</small>
                </a>
            ';

            $response[] = [
                'no'         => $no,
                'id'         => $row->id_kegiatan,
                'aksi'       => $aksi,
                'uker'       => $row->user->pegawai->uker->unit_kerja,
                'kode'       => $row->kode_kegiatan,
                'tanggal'    => Carbon::parse($row->tanggal_kegiatan)->isoFormat('DD MMM Y'),
                'kegiatan'   => $row->nama_kegiatan,
                'barang'     => $row->detail->count() . ' barang',
                'peserta'    => $row->jumlah_peserta . ' orang',
                'keterangan' => $row->keterangan,
                'file'       => $file
            ];

            $no++;
        }

        return response()->json($response);
    }

    public function item()
    {
        $role = Auth::user()->role_id;
        $uker = Auth::user()->pegawai->uker_id;
        $data = KegiatanDetail::join('t_kegiatan', 'id_kegiatan', 'kegiatan_id')->with('kegiatan.user.pegawai');

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

        $data     = KegiatanDetail::join('t_kegiatan', 'id_kegiatan', 'kegiatan_id')->with('kegiatan.user.pegawai');
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
                $foto = '<img src="' . asset('dist/img/foto_snaco/' . $row->snc->snc_foto) . '" class="img-fluid" alt="">';
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
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('dist/file/dakung/absensi'), $fileName);
        } else {
            $fileName = null;
        }

        $id_kegiatan = Kegiatan::withTrashed()->count() + 1;

        $tambah = new Kegiatan();
        $tambah->id_kegiatan      = $id_kegiatan;
        $tambah->user_id          = $request->user_id;
        $tambah->kode_kegiatan    = Str::random(5);
        $tambah->tanggal_kegiatan = $request->tanggal;
        $tambah->nama_kegiatan    = $request->kegiatan;
        $tambah->jumlah_peserta   = $request->peserta;
        $tambah->data_pendukung   = $fileName;
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
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $file->storeAs('public/file/data-pendukung/absensi', $fileName);
        } else {
            $fileName = null;
        }

        Kegiatan::where('id_kegiatan', $id)->update([
            'tanggal_kegiatan' => $request->tanggal,
            'nama_kegiatan'    => $request->kegiatan,
            'jumlah_peserta'   => $request->peserta,
            'keterangan'       => $request->keterangan,
            'data_pendukung'   => $fileName ?? $request->file
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

    public function viewPdf($id)
    {
        $data = Kegiatan::where('id_kegiatan', $id)->first();

        return view('pages.kegiatan.pdf', compact('id', 'data'));
    }

    public function deletePdf($id)
    {
        Kegiatan::where('id_kegiatan', $id)->update([
            'data_pendukung' => null
        ]);

        return redirect()->route('kegiatan.edit', $id);
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
        $snc  = KegiatanDetail::where('id_detail', $id)->first();
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
