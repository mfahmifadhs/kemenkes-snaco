<?php

namespace App\Http\Controllers;

use App\Models\Snackcorner;
use App\Models\SnackcornerKategori;
use App\Models\Usulan;
use App\Models\StokDetail;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $kategori = SnackcornerKategori::get();
        $snaco    = Snackcorner::with(['stokMasuk', 'stokKeluar'])->get();
        $role     = Auth::user()->role_id;
        $stokRoum = StokDetail::select('snc_id', DB::raw('sum(jumlah) as total'))->groupBy('snc_id')->get();
        $usulan   = Usulan::join('t_form', 'id_form', 'form_id')
            ->join('users', 'id', 'user_id')
            ->join('t_pegawai', 'id_pegawai', 'pegawai_id')
            ->where('kategori', 'snc');

        if ($role == 4) {
            $stok   = $snaco;
            $usulan = $usulan->where('uker_id', Auth::user()->pegawai->uker_id)->get();

            $stokMasuk  = $snaco->sum(function ($item) {
                return $item->stokMasukUker->sum('jumlah_permintaan');
            });

            $stokKeluar = $snaco->sum(function ($item) {
                return $item->stokKeluarUker->sum('jumlah');
            });

            return view('pages.user', compact('kategori', 'snaco', 'usulan', 'stok', 'stokMasuk', 'stokKeluar'));
        } else {
            $stok   = $snaco;
            $uker   = UnitKerja::where('utama_id', '46593')->get();
            $usulan = $usulan->get();

            $stokMasuk  = $snaco->sum(function ($item) {
                return $item->stokMasuk->sum('jumlah');
            });

            $stokKeluar = $snaco->sum(function ($item) {
                return $item->stokKeluar->sum('jumlah_permintaan');
            });
            return view('pages.index', compact('kategori', 'snaco', 'usulan', 'stok', 'uker', 'stokMasuk', 'stokKeluar'));
        }

    }
}
