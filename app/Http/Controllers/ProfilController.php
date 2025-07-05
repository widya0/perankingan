<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
public function EditProfile(Request $request)
    {
        $validated = $request->validate([
            'password' => 'nullable|string|min:6',
        ], [
        'password.min' => 'Password minimal harus 6 karakter.',
        ]);

        $user = Auth::user();

        if (isset($validated['password']) && $validated['password'] !== '') {
            $user->updatePasswordPengguna($validated['password']);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

}
