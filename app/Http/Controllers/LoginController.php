<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // Jika sudah login, arahkan langsung ke dashboard
            return redirect()->intended('/beranda');
        }

        return view('login');
    }

    public function login(Request $request)
    {

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $dataPengguna = User::where('username', $request->username)->first();


        // Menggunakan nama_user untuk login
        // Cek apakah user ditemukan
        if ($dataPengguna && Hash::check($request->password, $dataPengguna->password)) {
            Auth::login($dataPengguna);
            
            // Simpan nama admin di session
            Session::put('id_pengguna', $dataPengguna->id_pengguna);
            Session::put('nama_pengguna', $dataPengguna->username);
            Session::put('level_pengguna', $dataPengguna->level);

            return redirect()->intended('/beranda');
        }
        
        return back()->withErrors([
            'username' => 'Username atau Password salah.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/dashboard');
    }
}
