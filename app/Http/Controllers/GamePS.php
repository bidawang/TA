<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storage_M;

class GamePS extends Controller
{
    public function store(Request $request)
{
    $psId = $request->input('ps_id');
    $gameIds = $request->input('games', []);

    // Simpan masing-masing game_id ke dalam tabel gameps
    foreach ($gameIds as $gameId) {
        Storage_M::create([
            'id_ps' => $psId,
            'id_game' => $gameId,
        ]);
    }

    return redirect()->back()->with('success', 'Game berhasil ditambahkan ke PS.');
}

public function bulkDelete(Request $request)
{
    $ids = $request->input('selected_games', []);

    if (count($ids)) {
        Storage_M::whereIn('id', $ids)->delete();
        return redirect()->back()->with('success', 'Game berhasil dihapus.');
    }

    return redirect()->back()->with('warning', 'Tidak ada game yang dipilih.');
}



}
