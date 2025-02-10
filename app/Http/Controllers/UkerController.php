<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use Illuminate\Http\Request;

class UkerController extends Controller
{
    public function selectByUtama($id)
    {
        $data     = UnitKerja::where('utama_id', $id)->orderBy('unit_kerja', 'ASC')->get();
        $response = array();

        $response[] = array(
            "id"    => "",
            "text"  => "Semua Unit Kerja"
        );

        foreach($data as $row){
            $response[] = array(
                "id"    =>  $row->id_unit_kerja,
                "text"  =>  $row->unit_kerja
            );
        }

        return response()->json($response);
    }
}
