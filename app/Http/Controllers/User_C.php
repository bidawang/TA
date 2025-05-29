<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Wallet_M;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class User_C extends Controller
{
    // Tampilkan daftar user
    public function index()
{
    $users = User::where('role', '!=', 'developer')->get();
    return view('user.index', compact('users'));
}


    // Tampilkan detail user
    public function edit($google_id)
{
    $user = User::where('google_id', $google_id)->firstOrFail();
    return view('user.edit', compact('user'));
}

public function show($google_id)
{
    // Debugging: Memeriksa nilai google_id yang diterima
    $user = User::where('google_id', $google_id)->firstOrFail();
    return view('user.show', compact('user'));
}

    // Simpan perubahan phone & address
    public function update(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'phone' => 'nullable|string|max:20',
        'role' => 'nullable|in:user,admin,developer', // Validasi untuk role
        'status' => 'nullable|in:aktif,nonaktif,pending', // Validasi untuk status
    ]);

    // Cari user berdasarkan google_id
    $user = User::findOrFail($id);
// dd($request);
    // Update data user
    $user->update([
        'no_hp' => $request->phone,
        'role' => $request->role ?? Auth::user()->role ?? 'user',    // Update role
        'status' => Auth::user()->status, // Update status
    ]);

    // Redirect ke halaman detail user dengan pesan sukses
    return redirect()->route('user.show', $user->google_id)->with('success', 'Data berhasil diperbarui.');
}

public function profile($google_id)
{
    // Ambil data user berdasarkan google_id
    $user = User::where('google_id', $google_id)->firstOrFail();
    $wallets = Wallet_M::where('google_id', $user->google_id)->get();

    // Kirim data user ke view
    return view('user.profile', compact('user','wallets'));
}

}
