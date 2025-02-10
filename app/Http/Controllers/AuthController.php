<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use Hash;
use Auth;
use Session;
use DB;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class AuthController extends Controller
{

    public function index()
    {
        return view('login');
    }

    public function post(Request $request, $id)
    {
        if (Crypt::decrypt($id) == 'masuk.post') {
            $request->validate([
                'username'  => 'required',
                'password'  => 'required',
            ]);

            $credentials = $request->only('username', 'password');

            if (FacadesAuth::attempt($credentials)) {
                return redirect()->intended('dashboard')->with('success', 'Berhasil Masuk!');
            }

            return redirect()->route('login')->with('failed', 'Username atau Password Salah');
        } else {
            return back()->with('failed', 'Anda Tidak Memiliki Akses !');
        }
    }

    public function dashboard()
    {
        return redirect()->route('dashboard')->with('success', 'Hello');
    }

    public function email(Request $request)
    {
        $user  = Auth::user();
        $email = $request->email;

        if ($email) {
            $cekMail = User::where('email', $email)->where('id', '!=', $user->id)->first();

            if ($cekMail) {

                return redirect()->route('email')->with('failed', 'Email sudah terdaftar');
            } else {
                User::where('id', $user->id)->update([
                    'email' => $email
                ]);

                return redirect()->route('email')->with('success', 'Berhasil Menyimpan Perubahan');
            }
        } else {

            return view('pages.email', compact('user'));
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return Redirect('/');
    }
}
