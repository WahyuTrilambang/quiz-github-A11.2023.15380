<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8), 'different:current_password'],
        ], [
            'current_password.required'         => 'Password lama wajib diisi.',
            'current_password.current_password' => 'Password lama tidak cocok dengan akun Anda.',
            'password.required'                 => 'Password baru wajib diisi.',
            'password.min'                      => 'Password baru minimal harus 8 karakter.',
            'password.confirmed font'           => 'Konfirmasi password baru tidak cocok.',
            'password.different'                => 'Password baru tidak boleh sama dengan password lama.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password berhasil diperbarui!');
    }
}