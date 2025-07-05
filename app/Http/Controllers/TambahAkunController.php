<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TambahAkunController extends Controller
{
    public function index()
    {
        $dataTambahAkun = User::where('level', 'Petugas')->get();
        return view('tambahakun', compact('dataTambahAkun'));
    }

    public function TambahAkun(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'level' => 'required|in:Admin,Petugas',
        ]);

        $validated['password'] = bcrypt($request->password ?? '123');
        $validated['level'] = 'Petugas';

        User::create($validated);

        return redirect()->back()->with('success', 'Akun berhasil ditambahkan.');
    }

    public function editAkun(Request $request, $id)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'nullable|string|min:6',
            'level' => 'required|in:Admin,Petugas',
        ], [
        'username.required' => 'Username wajib diisi.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal harus 6 karakter.',
        ]);

        $pengguna = User::findOrFail($id);
        $pengguna->username = $validated['username'];
        $pengguna->level = $validated['level'];

        if ($validated['password']) {
            $pengguna->updatePasswordPengguna($validated['password']);
        } else {
            $pengguna->save(); // simpan perubahan selain password
        }

        return redirect()->back()->with('success', 'Akun berhasil diperbarui.');
    }

    public function HapusAkun($id)
    {
        try {
            $pengguna = User::findOrFail($id);
            $pengguna->delete();
            
            return redirect()->route('tambahakun')
                ->with('success', 'Akun berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus akun');
        }
    }

}
