<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\Role;
use App\Models\TimKerja;
use App\Models\UnitUtama;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show()
    {
        $user = User::get();
        return view('pages.user.show', compact('user'));
    }

    public function profil($id)
    {
        $user = User::where('id', $id)->first();
        return view('pages.user.profil.show', compact('user'));
    }

    public function profilEdit($id)
    {
        $user = User::where('id', $id)->first();
        return view('pages.user.profil.edit', compact('id','user'));
    }

    public function detail($id)
    {
        $user = User::where('id', $id)->first();
        return view('pages.user.detail', compact('user'));
    }

    public function create()
    {
        $role    = Role::get();
        $jabatan = Jabatan::get();
        $timker  = TimKerja::get();
        $utama   = UnitUtama::orderBy('unit_utama', 'ASC')->get();
        return view('pages.user.create', compact('role','utama','jabatan','timker'));
    }

    public function post(Request $request)
    {
        $pegawai = Pegawai::where('nip', $request->nip)->count();
        $user    = User::where('username', $request->username)->count();

        if ($user != 0 || $pegawai != 0) {
            return back()->with('failed', 'NIP / Username sudah terdaftar');
        }

        $id_pegawai = Pegawai::withTrashed()->count() + 1;
        $addPegawai = new Pegawai();
        $addPegawai->id_pegawai     = $id_pegawai;
        $addPegawai->uker_id        = $request->uker;
        $addPegawai->nip            = $request->nip;
        $addPegawai->nama_pegawai   = $request->pegawai;
        $addPegawai->jabatan_id     = $request->jabatan;
        $addPegawai->timker_id      = $request->timker;
        $addPegawai->status         = $request->status;
        $addPegawai->created_at     = Carbon::now();
        $addPegawai->save();

        $id_user = User::withTrashed()->count() + 1;
        $user = new User();
        $user->id            = $id_user;
        $user->role_id       = $request->role;
        $user->pegawai_id    = $id_pegawai;
        $user->deskripsi     = null;
        $user->username      = $request->username;
        $user->password      = Hash::make($request->password);
        $user->password_text = $request->password;
        $user->status        = $request->status;
        $user->created_at    = Carbon::now();
        $user->save();

        return redirect()->route('user.show')->with('success', 'Berhasil Menambah User');
    }

    public function edit($id)
    {
        $role    = Role::get();
        $jabatan = Jabatan::get();
        $timker  = TimKerja::get();
        $utama   = UnitUtama::orderBy('unit_utama', 'ASC')->get();
        $user    = User::where('id', $id)->first();
        return view('pages.user.edit', compact('id','role','utama','jabatan','timker','user'));
    }

    public function update(Request $request, $id)
    {
        $cekUser    = User::where('username', $request->username)->where('id', '!=', $id)->first();

        if ($cekUser) {
            return back()->with('failed', 'Username sudah terdaftar');
        }

        User::where('id', $id)->update([
            'role_id'       => $request->role,
            'pegawai_id'    => $request->pegawai,
            'deskripsi'     => null,
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
            'password_text' => $request->password,
            'status'        => $request->status,
        ]);

        return redirect()->route('user.show')->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function delete($id)
    {
        User::where('id', $id)->delete();
        return redirect()->route('user.show')->with('success', 'Berhasil Menghapus Data');
    }
}
