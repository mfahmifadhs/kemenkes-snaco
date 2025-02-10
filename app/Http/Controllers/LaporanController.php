<?php

namespace App\Http\Controllers;

use App\Imports\SnackcornerImport;
use App\Models\Snackcorner;
use App\Models\SnackcornerKategori;
use App\Models\UnitKerja;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function snaco(Request $request)
    {
        $uker  = UnitKerja::where('utama_id', '46593')->get();
        $bulan = $request->get('bulan') ?? Carbon::now()->format('m');
        $tahun = $request->get('tahun') ?? Carbon::now()->format('Y');
        return view('pages.snackcorner.laporan.show', compact('bulan', 'tahun', 'uker'));
    }

    public function snacoStok()
    {
        $snaco = Snackcorner::get();
        $uker  = UnitKerja::where('utama_id', '46593')->get();
        return view('pages.snackcorner.laporan.stok', compact('snaco', 'uker'));
    }

    public function snacoChart(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $result = SnackcornerKategori::with(['snc.permintaan' => function ($query) use ($bulan, $tahun) {
            $query->whereYear('created_at', $tahun);
            if ($bulan) {
                $query->whereMonth('created_at', $bulan);
            }
        }])
            ->get()
            ->map(function ($kategori) use ($bulan, $tahun) {
                $periode = Carbon::createFromFormat('m', $bulan)->isoFormat('MMMM') . ' ' . $tahun;

                return [
                    'periode' => $periode,
                    'barang'  => $kategori->nama_kategori,
                    'masuk'   => $kategori->snc->sum(fn($snc) => $snc->stokMasuk()->sum('jumlah')),
                    'total'   => $kategori->snc->sum(fn($snc) => $snc->permintaan->sum('jumlah_permintaan')),
                    'sisa'    => $kategori->snc->sum(fn($snc) => $snc->stokMasuk()->sum('jumlah')) - $kategori->snc->sum(fn($snc) => $snc->permintaan->sum('jumlah_permintaan')),
                ];
            });

        return response()->json($result);
    }
}
