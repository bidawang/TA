<?php

namespace App\Http\Controllers;

use App\Models\PS_M;
use App\Models\Platform_M;
use App\Models\GamePS_M;
use App\Models\Game_M;
use App\Models\Storage_M;
use Illuminate\Http\Request;

class PS extends Controller
{
    public function index()
    {
        $psList = PS_M::all();
        return view('ps.index', compact('psList'));
    }

    public function create()
    {
        return view('ps.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'model_ps' => 'required',
            'storage' => 'required',
            'tipe' => 'required',
            'seri' => 'required',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Handle upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-ps', 'public');
        }

        PS_M::create([
            'model_ps' => $request->model_ps,
            'storage' => $request->storage,
            'tipe' => $request->tipe,
            'seri' => $request->seri,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('ps.index')->with('success', 'Data PS berhasil ditambahkan.');
    }

    public function show($id)
{
    // Cari PS berdasarkan ID
    $ps = PS_M::findOrFail($id);

    // Ambil platform berdasarkan nama PS
    $platform = Platform_M::where('name', $ps->model_ps)->first();
    // Ambil game yang sudah dimiliki PS ini
    $ownedGames = Storage_M::where('id_ps', $ps->id)->with('game')->get();
    $ownedGameIds = $ownedGames->pluck('id_game'); // ini penting
    // Cek jika platform ditemukan
    if ($platform) {
        // Ambil semua ID game berdasarkan platform ini
        $gameIds = GamePS_M::where('platform_id', $platform->id)->pluck('game_id');
        
        // Ambil data game berdasarkan ID dan exclude game yang sudah dimiliki
        $games = Game_M::whereIn('game_id', $gameIds)
        ->whereNotIn('id', $ownedGameIds)
            ->orderBy('name')
            ->get();
            
        // Debug (opsional)
        // dd($games);
    } else {
        $games = collect(); // Kosong jika platform tidak ditemukan
    }

    return view('ps.show', compact('ps', 'games', 'ownedGames'));
}





    public function edit($id)
    {
        $ps = PS_M::findOrFail($id);
        return view('ps.edit', compact('ps'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'model_ps' => 'required',
            'storage' => 'required',
            'tipe' => 'required',
            'seri' => 'required',
            'foto' => 'nullable|image|max:2048',
        ]);

        $ps = PS_M::findOrFail($id);

        // Jika upload foto baru
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-ps', 'public');
            $ps->foto = $fotoPath;
        }

        $ps->update([
            'model_ps' => $request->model_ps,
            'storage' => $request->storage,
            'tipe' => $request->tipe,
            'seri' => $request->seri,
            'foto' => $ps->foto,
        ]);

        return redirect()->route('ps.index')->with('success', 'Data PS berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ps = PS_M::findOrFail($id);
        $ps->delete();

        return redirect()->route('ps.index')->with('success', 'Data PS berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_games' => 'required|array',
            'selected_games.*' => 'exists:storage,id',
        ]);

        Storage_M::whereIn('id', $request->selected_games)->delete();

        return back()->with('success', 'Game yang dipilih berhasil dihapus.');
    }
}
