<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TV_M;

class TV extends Controller
{
    public function index()
{
    $idRental = session('id_rental');
    $googleId = Auth::user()->google_id;

    $tvList = TV_M::where('id_rental', $idRental)
                 ->where('google_id', $googleId)
                 ->get();

    return view('tv.index', compact('tvList'));
}


    public function create()
    {
        return view('tv.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'merek' => 'required',
            'ukuran' => 'required',
        ]);

        TV_M::create([
            'merek' => $request->merek,
            'ukuran' => $request->ukuran,
            'id_rental' => session('id_rental'),
            'google_id' => Auth::user()->google_id,
        ]);

        return redirect()->route('tv.index')->with('success', 'Data TV berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $tv = TV_M::findOrFail($id);
        return view('tv.edit', compact('tv'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'merek' => 'required',
            'ukuran' => 'required',
        ]);

        $tv = TV_M::findOrFail($id);

        $tv->update([
            'merek' => $request->merek,
            'ukuran' => $request->ukuran,
            'tipe' => $request->tipe,
        ]);

        return redirect()->route('tv.index')->with('success', 'Data TV berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tv = TV_M::findOrFail($id);
        $tv->delete();

        return redirect()->route('tv.index')->with('success', 'Data TV berhasil dihapus.');
    }
}
