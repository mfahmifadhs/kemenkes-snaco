<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Role;
use App\Models\TimKerja;
use App\Models\UnitUtama;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function show()
    {
        $pegawai = Pegawai::orderBy('status', 'asc')->get();
        return view('pages.pegawai.show', compact('pegawai'));
    }

    public function edit($id)
    {
        $role    = Role::get();
        $jabatan = Jabatan::get();
        $timker  = TimKerja::get();
        $utama   = UnitUtama::orderBy('unit_utama', 'ASC')->get();
        $data    = Pegawai::where('id_pegawai', $id)->first();
        return view('pages.pegawai.edit', compact('id','role','utama','jabatan','timker','data'));
    }

    public function update(Request $request, $id)
    {
        $cekPegawai = Pegawai::where('nip', $request->nip)->where('id_pegawai','!=', $id)->first();

        if ($cekPegawai) {
            return back()->with('failed', 'NIP sudah terdaftar');
        }

        Pegawai::where('id_pegawai', $id)->update([
            'uker_id'      => $request->uker,
            'nip'          => $request->nip,
            'nama_pegawai' => $request->pegawai,
            'jabatan_id'   => $request->jabatan,
            'timker_id'    => $request->timker,
            'status'       => $request->status,
        ]);

        return redirect()->route('pegawai.show')->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function selectByUker($id)
    {
        $data     = Pegawai::where('uker_id', $id)->orderBy('nama_pegawai', 'ASC')->get();
        $response = array();

        $response[] = array(
            "id"    => "",
            "text"  => "Semua Pegawai"
        );

        foreach($data as $row){
            $response[] = array(
                "id"    =>  $row->id_pegawai,
                "text"  =>  $row->nama_pegawai
            );
        }

        return response()->json($response);
    }
}
