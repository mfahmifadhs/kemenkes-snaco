<?php

namespace App\Http\Controllers;

use App\Imports\SnackcornerImport;
use App\Models\Satuan;
use App\Models\SnackcornerKategori;
use App\Models\SnackcornerKeranjang;
use App\Models\Snackcorner;
use App\Models\Stok;
use App\Models\StokDetail;
use App\Models\UsulanSnc;
use App\Models\Usulan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Auth;
use Str;
use DB;

class SnackcornerController extends Controller
{
    public function index()
    {
        $kategori = SnackcornerKategori::get();
        $snaco    = Snackcorner::where('snc_status', 'true')->get();
        $role     = Auth::user()->role_id;
        $usulan   = Usulan::join('t_form', 'id_form', 'form_id')
            ->join('users', 'id', 'user_id')
            ->join('t_pegawai', 'id_pegawai', 'pegawai_id')
            ->where('kategori', 'snc');

        if ($role == 4) {
            $usulan = $usulan->where('uker_id', Auth::user()->pegawai->uker_id)->get();
        } else {
            $usulan = $usulan->get();
        }

        return view('pages.snackcorner.index', compact('kategori', 'snaco', 'usulan'));
    }

    public function detail($id)
    {
        $data = Usulan::where('id_usulan', $id)->first();
        return view('pages.snackcorner.detail', compact('data'));
    }

    public function show(Request $request)
    {
        $data     = Snackcorner::orderBy('id_snc', 'asc')->get();
        $kategori = $request->get('kategori');
        $barang   = $request->get('barang');
        $status   = $request->get('status');

        $listKategori = SnackcornerKategori::orderBy('nama_kategori', 'asc')->get();
        $listSatuan   = Satuan::orderBy('satuan', 'asc')->get();

        return view('pages.snackcorner.show', compact('data', 'kategori', 'barang', 'status', 'listKategori', 'listSatuan'));
    }

    public function update(Request $request)
    {
        $data = Snackcorner::where('id_snc', $request->id_snc)->first();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = $file->getClientOriginalName();
            $request->foto->move(public_path('dist/img/foto_snaco'), $fileName);
        }

        Snackcorner::where('id_snc', $request->id_snc)->update([
            'snc_kategori'   => $request->snc_kategori,
            'snc_nama'       => $request->snc_nama,
            'snc_deskripsi'  => $request->snc_deskripsi,
            'snc_satuan'     => $request->snc_satuan,
            'snc_harga'      => (int)str_replace('.', '', $request->snc_harga),
            'snc_keterangan' => $request->snc_keterangan,
            'snc_foto'       => $fileName ?? $data->snc_foto,
            'snc_status'     => $request->snc_status,
        ]);

        return redirect()->back()->with('Berhasil Menyimpan Perubahan');
    }

    public function selectAll(Request $request)
    {
        // dd($request->all());
        $role     = Auth::user()->role_id;
        $kategori = $request->kategori;
        $barang   = $request->barang;
        $status   = $request->status;
        $search   = $request->search;

        $data    = Snackcorner::orderBy('id_snc', 'asc')->orderBy('snc_status', 'desc');
        $no       = 1;
        $response = [];

        if ($kategori || $barang || $search) {
            if ($kategori) {
                $res = $data->whereHas('kategori', function ($query) use ($kategori) {
                    $query->where('id_kategori', $kategori);
                });
            }

            if ($barang) {
                $res = $data->where('id_snc', $barang);
            }

            if ($search) {
                $res = $data->where('snc_nama', 'like', '%' . $search . '%')
                    ->orWhere('snc_deskripsi', 'like', '%' . $search . '%');
            }

            $result = $res->get();
        } else {
            $result = $data->get();
        }

        if ($status) {
            if (Auth::user()->role_id == 4) {
                // Jika Role 4, maka filter berdasarkan stok Uker
                $result = $result->filter(function ($row) {
                    return $row->stokUker(Auth::user()->pegawai->uker_id) > 0;
                });
            } else {
                // Jika bukan Role 4, filter berdasarkan stok umum
                $result = $result->filter(function ($row) use ($status) {
                    $stok = $row->stok();

                    if ($status == 'true') {
                        return $stok > 0;
                    } elseif ($status == 'false') {
                        return $stok == 0;
                    }

                    return true;
                });
            }
        }

        foreach ($result as $row) {
            $aksi   = '';
            $status = '';

            if ($role == 4) {
                $stok   = number_format($row->stokUker(Auth::user()->pegawai->uker_id), 0, '.');
            } else {
                $stok = number_format($row->stok(), 0, '.');
            }

            if ($row->snc_foto) {
                $foto = '<img src="' . asset('dist/img/foto_snaco/' . $row->snc_foto) . '" class="img-fluid" alt="">';
            } else {
                $foto = '<img src="https://cdn-icons-png.flaticon.com/512/679/679821.png" class="img-fluid" alt="">';
            }

            if ($stok != 0) {
                $status = '<span class="badge badge-success p-1 w-100"><i class="fas fa-check-circle"></i> Tersedia</span>';
            } else {
                $status = '<span class="badge badge-danger p-1 w-100"><i class="fas fa-times-circle"></i> Tidak Tersedia</span>';
            }

            if ($role != 4) {
                $aksi .= '
                    <a href="' . route('snaco.detail.item', $row->id_snc) . '" class="btn btn-default btn-xs bg-primary rounded border-dark">
                        <i class="fas fa-info-circle p-1" style="font-size: 12px;"></i>
                    </a>
                ';
            }

            $response[] = [
                'no'         => $no,
                'id'         => $row->id_snc,
                'aksi'       => $aksi,
                'foto'       => $foto,
                'fileFoto'   => $row->snc_foto,
                'kategori'   => $row->kategori->nama_kategori,
                'barang'     => $row->snc_nama,
                'deskripsi'  => $row->snc_deskripsi ?? '',
                'harga'      => 'Rp' . number_format($row->snc_harga, 0, '.'),
                'satuan'     => $row->satuan->satuan,
                'maksimal'   => $row->snc_maksimal,
                'stok'       => $stok,
                'keterangan' => $row->snc_keterangan ?? '',
                'status'     => $status,
                'role'       => $role
            ];

            $no++;
        }

        return response()->json($response);
    }

    public function snc($id)
    {
        $data = Snackcorner::with('kategori')->find($id);

        if ($data) {
            return response()->json($data);
        }

        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    // ===============================================
    //                PROSES PENYERAHAN
    // ===============================================

    public function proses(Request $request, $id)
    {
        $data     = Usulan::where('id_usulan', $id)->first();

        if (!$request->all() && $data->status_proses != 'proses') {
            return redirect()->route('snaco.detail', $id)->with('failed', 'Permintaan tidak dapat di proses');
        }

        if (!$request->all()) {
            $kategori = SnackcornerKategori::orderBy('nama_kategori', 'ASC')->get();
            return view('pages.snackcorner.proses', compact('data', 'kategori'));
        } else {
            Usulan::where('id_usulan', $id)->update([
                'nama_penerima'      => $request->penerima,
                'status_proses'      => $request->proses,
                'otp_4'              => rand(111111, 999999),
            ]);

            foreach ($data->usulanSnc as $row) {
                Usulansnc::where('usulan_id', $id)->update([
                    'status'     => 'true',
                    'created_at' =>  $data->tanggal_ambil,
                ]);
            }

            return redirect()->route('snaco.detail', $id)->with('success', 'Berhasil Melakukan Serah Terima');
        }
    }

    // ===============================================
    //              SELECT SNACK CORNER
    // ===============================================

    public function select($id)
    {
        $data = Snackcorner::where('snc_kategori', $id)->orderBy('snc_nama', 'ASC')->where('snc_status', 'true')->get();
        $response = array();

        $response[] = array(
            "id"    => "",
            "text"  => "-- Pilih Barang --"
        );

        foreach ($data as $row) {
            $response[] = array(
                "id"     => $row->id_snc,
                "text"   => $row->snc_nama . ' ' . $row->snc_deskripsi,
                "satuan" => $row->satuan->satuan
            );
        }

        return response()->json($response);
    }

    // ===============================================
    //                   OPEN ITEM
    // ===============================================

    public function addItem(Request $request)
    {
        $snc    = UsulanSnc::where('snc_id', $request->id_snc)->where('usulan_id', $request->usulan_id)->first();
        $usulan = Usulan::where('id_usulan', $request->usulan_id)->first();

        if ($snc) {
            $total = $snc->jumlah_permintaan + $request->jumlah;
            UsulanSnc::where('id_usulan_snc', $snc->id_usulan_snc)->update([
                'jumlah_permintaan' => $total,
                'status'            => $snc->usulan->status_persetujuan == 'true' ? 'true' : 'false'
            ]);
        } else {
            $id_usulan_snc = UsulanSnc::withTrashed()->count() + 1;
            $tambah = new UsulanSnc();
            $tambah->id_usulan_snc      = $id_usulan_snc;
            $tambah->usulan_id          = $request->usulan_id;
            $tambah->snc_id             = $request->id_snc;
            $tambah->jumlah_permintaan  = $request->jumlah;
            $tambah->status             = $usulan->status_persetujuan == 'true' ? 'true' : 'false';
            $tambah->created_at         = Carbon::now();
            $tambah->save();
        }

        return redirect()->back()->with('success', 'Berhasil Menyimpan');
    }

    public function updateItem(Request $request)
    {
        $snc = UsulanSnc::where('snc_id', $request->id_snc)->where('usulan_id', $request->usulan_id)->first();
        if ($snc) {
            $total = $snc->jumlah_permintaan + $request->jumlah;
            UsulanSnc::where('id_usulan_snc', $snc->id_usulan_snc)->update([
                'jumlah_permintaan' => $request->jumlah,
                'status'            => $snc->usulan->status_persetujuan == 'true' ? 'true' : 'false'
            ]);
        } else {
            UsulanSnc::where('id_usulan_snc', $request->id_usulan_snc)->update([
                'snc_id'            => $request->id_snc,
                'jumlah_permintaan' => $request->jumlah,
                'status'            => $snc->usulan->status_persetujuan == 'true' ? 'true' : 'false'
            ]);
        }

        return redirect()->back()->with('success', 'Berhasil Menyimpan');
    }

    public function deleteItem($id)
    {
        UsulanSnc::where('id_usulan_snc', $id)->delete();

        return redirect()->back()->with('success', 'Berhasil Menghapus');
    }

    // ===============================================
    //                 DAFTAR USULAN
    // ===============================================


    public function usulan(Request $request)
    {
        $role    = Auth::user()->role_id;
        $jabatan = Auth::user()->pegawai->jabatan_id;

        $aksi    = $request->aksi;
        $id      = $request->id;
        $data    = Usulan::orderBy('status_persetujuan', 'asc')->orderBy('status_proses', 'asc')->orderBy('tanggal_usulan', 'desc')
            ->join('t_form', 'id_form', 'form_id')
            ->where('kategori', 'snc');
        $no       = 1;
        $response = [];

        if ($request->uker || $request->proses || $request->tanggal || $request->bulan || $request->tahun) {
            if ($request->uker) {
                $res = $data->whereHas('user.pegawai.uker', function ($query) use ($request) {
                    $query->where('id_unit_kerja', $request->uker);
                });
            }

            if ($request->proses == 'verif') {
                $res = $data->whereNull('status_persetujuan');
            }

            if ($request->proses == 'false') {
                $res = $data->where('status_persetujuan', $request->proses);
            }

            if ($request->proses == 'proses' || $request->proses == 'selesai') {
                $res = $data->where('status_proses', $request->proses);
            }

            if ($request->tanggal) {
                $res = $data->whereDay('tanggal_usulan', $request->tanggal);
            }

            if ($request->bulan) {
                $res = $data->whereMonth('tanggal_usulan', $request->bulan);
            }

            if ($request->tahun) {
                $res = $data->whereYear('tanggal_usulan', $request->tahun);
            }

            $result = $res;
        } else if ($aksi == 'status_proses_id') {
            $result = $data->where($aksi, $id);
        } else if ($aksi == 'status_pengajuan_id') {
            $result = $data->where($aksi, $id);
        } else {
            $result = $data;
        }

        if ($role == 4) {
            $result = $result->where('user_id', Auth::user()->id)->get();
        } else {
            $result = $result->get();
        }

        foreach ($result as $row) {

            if ($row->status_persetujuan == 'true') {
                $status = '<span class="badge badge-success p-1 w-100"><i class="fas fa-check-circle"></i> Setuju</span>';
            } else if ($row->status_persetujuan == 'false') {
                $status = '<span class="badge badge-danger p-1 w-100"><i class="fas fa-times-circle"></i> Tolak</span>';
            } else if (!$row->otp_1) {
                $status = '<span class="badge badge-danger p-1 w-100"><i class="fas fa-exclamation-circle"></i> Verif</span>';
            } else {
                $status = '<span class="badge badge-warning p-1 w-100"><i class="fas fa-clock"></i> Pending</span>';
            }

            if ($row->status_proses == 'proses') {
                $proses = '<span class="badge badge-warning p-1 w-100"><i class="fas fa-clock"></i> Proses</span>';
            } else if ($row->status_proses == 'selesai') {
                $proses = '<span class="badge badge-success p-1 w-100"><i class="fas fa-check-circle"></i> Selesai</span>';
            } else {
                $proses = '';
            }

            $aksi = '';

            if (Auth::user()->access == 'adm-verif' && !$row->status_persetujuan) {
                $aksi .= '
                    <a href="' . route('usulan.verif', $row->id_usulan) . '" class="btn btn-default btn-xs bg-warning rounded">
                        <i class="fas fa-file-signature p-1" style="font-size: 12px;"></i>
                    </a>';
            } else if (Auth::user()->access == 'adm-proses' && $row->status_proses == 'proses') {
                $aksi .= '
                    <a href="' . route('snaco.proses', $row->id_usulan) . '" class="btn btn-default btn-xs bg-warning rounded">
                        <i class="fas fa-file-import p-1" style="font-size: 12px;"></i>
                    </a>';
            } else {
                $aksi .= '
                    <a href="' . route('snaco.detail', $row->id_usulan) . '" class="btn btn-default btn-xs bg-primary rounded">
                        <i class="fas fa-info-circle p-1" style="font-size: 12px;"></i>
                    </a>';
            }

            $response[] = [
                'no'        => $no,
                'id'        => $row->id_usulan,
                'aksi'      => $aksi,
                'kode'      => $row->kode_usulan,
                'tanggal'   => Carbon::parse($row->tanggal_usulan)->isoFormat('HH:mm | DD MMM Y'),
                'uker'      => ucwords(strtolower($row->user?->pegawai->uker->unit_kerja)),
                'nosurat'   => $row->no_surat_usulan ?? '-',
                'totalItem' => $row->usulanSnc->count(),
                'hal'       => Str::limit($row->keterangan, 100),
                'deskripsi' => $row->usulanSnc->map(function ($item) {
                    return Str::limit(' ' . $item->snc->snc_nama . ' ' . $item->snc->snc_deskripsi . ' (' . $item->jumlah_permintaan . ' ' . $item->snc->satuan->satuan . ')', 150);
                }),
                'status'     => $status . '<br>' . $proses
            ];

            $no++;
        }

        return response()->json($response);
    }

    // ===============================================
    //                 KERANJANG
    // ===============================================

    public function storeBucket(Request $request)
    {
        $bucket = SnackcornerKeranjang::where('user_id', Auth::user()->id)->where('snc_id', $request->snc_id)->first();

        if ($bucket) {
            SnackcornerKeranjang::where('id_keranjang', $bucket->id_keranjang)->update([
                'kuantitas' => $bucket->kuantitas + (int) str_replace('.', '', $request->qty)
            ]);
        } else {
            $tambah = new SnackcornerKeranjang();
            $tambah->user_id    = Auth::user()->id;
            $tambah->snc_id     = $request->snc_id;
            $tambah->kuantitas  = (int) str_replace('.', '', $request->qty);
            $tambah->status_id  = $request->status_id;
            $tambah->created_at = Carbon::now();
            $tambah->save();
        }

        $dataCartCount  = SnackcornerKeranjang::where('user_id', Auth::user()->id);
        $dataCartBasket = SnackcornerKeranjang::where('user_id', Auth::user()->id)
            ->join('t_snc', 'id_snc', 'snc_id')
            ->join('t_snc_kategori', 'id_kategori', 'snc_kategori')
            ->join('t_satuan', 'id_satuan', 'snc_satuan')
            ->orderBy('id_keranjang', 'ASC');

        if ($request->status_id) {
            $cartCount  = $dataCartCount->where('snc_keranjang.status_id', $request->status_id)->count();
            $cartBasket = $dataCartBasket->where('snc_keranjang.status_id', $request->status_id)->get();
        } else {
            $cartCount  = $dataCartCount->count();
            $cartBasket = $dataCartBasket->get();
        }

        return response()->json(['message' => 'Item berhasil ditambahkan ke keranjang', 'cartCount' => $cartCount, 'cartBasket' => $cartBasket]);
    }

    public function updateBucket(Request $request, $aksi, $id)
    {
        $bucket = SnackcornerKeranjang::where('id_keranjang', $id)->first();

        if ($aksi == 'min') {
            $kuantitas = $bucket->kuantitas - 1;
        } else {
            $kuantitas = $bucket->kuantitas + 1;
        }

        if ($bucket) {
            SnackcornerKeranjang::where('id_keranjang', $id)->update([
                'kuantitas' => $kuantitas
            ]);

            $updated = SnackcornerKeranjang::where('id_keranjang', $id)->first();
        } else {
            Usulan::where('id_usulan_snc', $id)->update([
                'jumlah_permintaan' => $kuantitas
            ]);

            $updated = Usulan::where('id_usulan_snc', $id)->first();
        }

        return response()->json(['updatedKuantitas' => $updated]);
    }

    public function removeBucket($id)
    {
        SnackcornerKeranjang::where('id_keranjang', $id)->delete();

        $cartCount  = SnackcornerKeranjang::where('user_id', Auth::user()->id)->count();
        $cartBasket = SnackcornerKeranjang::where('user_id', Auth::user()->id)
            ->join('t_snc', 'id_snc', 'snc_id')->join('t_snc_kategori', 'id_kategori', 'snc_kategori')
            ->orderBy('id_keranjang', 'ASC')
            ->get();

        return response()->json(['message' => 'Item berhasil ditambahkan ke keranjang', 'cartCount' => $cartCount, 'cartBasket' => $cartBasket]);
    }

    // ===============================================
    //                 STOK BARANG
    // ===============================================

    public function stok()
    {
        $stok = Stok::get();
        return view('pages.snackcorner.stok.show', compact('stok'));
    }

    public function stokDetail($id)
    {
        $stok = Stok::where('id_stok', $id)->first();
        return view('pages.snackcorner.stok.detail', compact('stok'));
    }

    public function stokCreate(Request $request)
    {
        $barang     = Auth::user()->keranjang;
        $keterangan = $request->get('keterangan');
        $kategori   = SnackcornerKategori::get();
        return view('pages.snackcorner.stok.create', compact('keterangan', 'barang', 'kategori'));
    }

    public function stokStore(Request $request)
    {
        $user       = Auth::user()->id;
        $detail     = SnackcornerKeranjang::where('user_id', $user)->get();
        $harga      = (int)str_replace('.', '', $request->total_harga);

        $id_stok = Stok::withTrashed()->count() + 1;

        foreach ($detail as $row) {
            $id_detail = StokDetail::withTrashed()->count() + 1;
            $detail = new StokDetail();
            $detail->id_detail  = $id_detail;
            $detail->stok_id    = $id_stok;
            $detail->snc_id     = $row->snc_id;
            $detail->jumlah     = $row->kuantitas;
            $detail->created_at = Carbon::now();
            $detail->save();

            SnackcornerKeranjang::where('id_keranjang', $row->id_keranjang)->delete();
        }

        $stok = new Stok();
        $stok->id_stok = $id_stok;
        $stok->tanggal_masuk = $request->tanggal_masuk;
        $stok->no_kwitansi   = $request->no_kwitansi;
        $stok->total_harga   = $harga;
        $stok->keterangan    = $request->keterangan;
        $stok->created_at    = Carbon::now();
        $stok->save();

        return redirect()->route('snaco.stok.show')->with('success', 'Berhasil');
    }

    public function stokEdit($id)
    {
        $stok     = Stok::where('id_stok', $id)->first();
        $kategori = SnackcornerKategori::get();
        return view('pages.snackcorner.stok.edit', compact('stok', 'kategori'));
    }

    public function stokUpdate(Request $request, $id)
    {
        $harga = (int)str_replace('.', '', $request->total_harga);

        Stok::where('id_stok', $id)->update([
            'tanggal_masuk' => $request->tanggal_masuk,
            'no_kwitansi'   => $request->no_kwitansi,
            'total_harga'   => $harga,
            'keterangan'    => $request->keterangan
        ]);

        return redirect()->route('snaco.stok.detail', $id)->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function stokDelete($id)
    {
        StokDetail::where('stok_id', $id)->delete();
        Stok::where('id_stok', $id)->delete();

        return redirect()->route('snaco.stok.show')->with('success', 'Berhasil Menghapus');
    }

    public function stokItemStore(Request $request)
    {
        $stok = $request->stok_id;

        // Edit Keranjang
        if (!$stok) {
            $user = Auth::user()->id;
            $snc  = SnackcornerKeranjang::where('snc_id', $request->id_snc)->where('user_id', $user)->first();
            $qty  = (int)str_replace('.', '', $request->jumlah);

            if ($snc) {
                $total = $snc->kuantitas + $qty;
                SnackcornerKeranjang::where('id_keranjang', $snc->id_keranjang)->update([
                    'kuantitas' => $total
                ]);
            } else {
                $id_keranjang = SnackcornerKeranjang::withTrashed()->count() + 1;
                $tambah = new SnackcornerKeranjang();
                $tambah->id_keranjang = $id_keranjang;
                $tambah->user_id      = $user;
                $tambah->snc_id       = $request->id_snc;
                $tambah->kuantitas    = $qty;
                $tambah->created_at   = Carbon::now();
                $tambah->save();
            }
        }

        // Edit Stok
        if ($stok) {
            $snc  = StokDetail::where('snc_id', $request->id_snc)->where('stok_id', $stok)->first();
            $qty  = (int)str_replace('.', '', $request->jumlah);

            if ($snc) {
                $total = $snc->jumlah + $qty;
                StokDetail::where('id_detail', $snc->id_detail)->update([
                    'jumlah' => $total
                ]);
            } else {
                $id_detail = StokDetail::withTrashed()->count() + 1;
                $tambah = new StokDetail();
                $tambah->id_detail    = $id_detail;
                $tambah->stok_id      = $stok;
                $tambah->snc_id       = $request->id_snc;
                $tambah->jumlah       = $qty;
                $tambah->created_at   = Carbon::now();
                $tambah->save();
            }
        }

        return redirect()->back()->with('success', 'Berhasil Menyimpan');
    }

    public function stokItemUpdate(Request $request, $id)
    {
        $stok = $request->stok_id;

        // Edit Keranjang
        if (!$stok) {
            $user = Auth::user()->id;
            $snc  = SnackcornerKeranjang::where('snc_id', $request->id_snc)->where('user_id', $user)->first();
            $qty  = (int)str_replace('.', '', $request->jumlah);

            if ($snc) {
                $total = $snc->jumlah_permintaan + $qty;
                SnackcornerKeranjang::where('id_keranjang', $snc->id_keranjang)->update([
                    'kuantitas' => $qty
                ]);
            } else {
                SnackcornerKeranjang::where('id_keranjang', $request->id_keranjang)->update([
                    'snc_id'    => $request->id_snc,
                    'kuantitas' => $qty
                ]);
            }
        }

        // Edit Stok
        if ($stok) {
            $snc  = StokDetail::where('snc_id', $request->id_snc)->where('stok_id', $stok)->first();
            $qty  = (int)str_replace('.', '', $request->jumlah);

            if ($snc) {
                StokDetail::where('id_detail', $snc->id_detail)->update([
                    'jumlah' => $qty
                ]);
            } else {
                StokDetail::where('id_detail', $id)->update([
                    'snc_id' => $request->id_snc,
                    'jumlah' => $qty
                ]);
            }
        }

        return redirect()->back()->with('success', 'Berhasil Menyimpan');
    }

    public function stokItemDelete(Request $request, $aksi, $id)
    {
        if ($aksi != 'stok') {
            SnackcornerKeranjang::where('id_keranjang', $id)->delete();
        }

        if ($aksi == 'stok') {
            StokDetail::where('id_detail', $id)->delete();
        }

        return redirect()->back()->with('success', 'Berhasil Menghapus');
    }


    // ===============================================
    //                STOK BARANG UKER
    // ===============================================

    public function stokUker($id)
    {
        $role   = Auth::user()->role_id;
        $uker   = $role == 4 ? Auth::user()->pegawai->uker_id : null;
        $barang = Snackcorner::findOrFail($id);
        $stok   = $barang->stokUker($uker);

        return response()->json([
            'stok' => $stok
        ]);
    }

    // ===============================================
    //                UPLOAD BARANG
    // ===============================================

    public function detailItem($id)
    {
        $data = Snackcorner::where('id_snc', $id)->first();
        return view('pages.snackcorner.barang.detail', compact('id', 'data'));
    }

    public function upload(Request $request)
    {
        Excel::import(new SnackcornerImport(), $request->file);
        return redirect()->route('snaco.show')->with('success', 'Berhasil Upload');
    }
}
