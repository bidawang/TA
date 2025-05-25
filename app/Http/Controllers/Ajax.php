<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi_M;
class Ajax extends Controller
{
    public function getTransaksi()
{
    $user = auth()->user();

    $transaksiNotif = Transaksi_M::with('rental', 'setRental')
        ->when($user->role === 'user', function ($q) use ($user) {
            $q->where('google_id', $user->google_id)
              ->whereIn('status', ['disetujui']);
        })
        ->when($user->role === 'admin', function ($q) use ($user) {
            $q->whereIn('status', ['pending'])
              ->whereHas('rental', fn($qr) => $qr->where('google_id', $user->google_id));
        })
        ->latest()
        ->take(5)
        ->get();

    return response()->json($transaksiNotif);
}

}
