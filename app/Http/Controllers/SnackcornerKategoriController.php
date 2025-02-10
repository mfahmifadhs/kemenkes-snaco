<?php

namespace App\Http\Controllers;

use App\Models\SnackcornerKategori;
use Illuminate\Http\Request;

class SnackcornerKategoriController extends Controller
{
    public function show()
    {
        $data = SnackcornerKategori::get();
        return view('pages.snackcorner.kategori.show', compact('data'));
    }

    public function detail($id)
    {
        $data = SnackcornerKategori::where('id_kategori', $id)->first();
        return view('pages.snackcorner.kategori.detail', compact('data'));
    }

    public function create()
    {
        return view('pages.snackcorner.kategori.create');
    }

    public function post(Request $request)
    {
        $id_kategori = SnackcornerKategori::withTrashed()->count() + 1;
        $tambah = new SnackcornerKategori();
        $tambah->id_kategori   = $id_kategori;
        $tambah->nama_kategori = $request->nama_kategori;
        $tambah->deskripsi     = $request->deskripsi;
        $tambah->icon          = $request->icon;
        $tambah->status        = $request->status;
        $tambah->save();

        return redirect()->route('jenis-snaco.show')->with('success', 'Berhasil Menambah Jenis Barang');
    }

    public function edit($id)
    {
        $data = SnackcornerKategori::where('id_kategori', $id)->first();
        return view('pages.snackcorner.kategori.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        SnackcornerKategori::where('id_kategori', $id)->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi'     => $request->deskripsi,
            'icon'          => $request->icon,
            'status'        => $request->status
        ]);

        return redirect()->route('jenis-snaco.show')->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function delete($id)
    {
        SnackcornerKategori::where('id_kategori', $id)->update([
            'status'        => 'false'
        ]);

        return redirect()->route('jenis-snaco.show')->with('success', 'Berhasil Menonaktifkan Barang');
    }

}
