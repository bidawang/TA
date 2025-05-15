<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use App\Models\SetRental_M;
use App\Models\TV_M;
use App\Models\PS_M;
use Illuminate\Http\Request;

class SetRental extends Controller
{
    public function getTripayChannels()
{
    $apiKey = env('TRIPAY_API_KEY');

    $response = Http::withHeaders([
        'Authorization' => "Bearer $apiKey"
    ])->get('https://tripay.co.id/api-sandbox/merchant/payment-channel');

    if ($response->successful()) {
        // Mengambil data channel pembayaran
        return $response->json()['data'];
    }

    return []; // fallback jika gagal
}
    public function index(Request $request)
{
    $rental_id = $request->query('rental_id');

    $query = SetRental_M::with(['tv', 'ps', 'rental']);

    if ($rental_id) {
        $query->where('rental_id', $rental_id);
    }

    $setRentals = $query->get();

    // Panggil Tripay
    $channels = $this->getTripayChannels();
// dd($channels);
    // Kirim ke view
    return view('setrental.index', [
        'setRentals' => $setRentals,
        'rental_id' => $rental_id,
        'tripayChannels' => $channels,
        'userGoogleId' => auth()->user()->google_id ?? null,
    ]);
}


    public function create(Request $request)
    {
        $rental_id = $request->query('rental_id');
        $tvs = TV_M::all();
        $ps = PS_M::all();

        return view('setrental.create', compact('tvs', 'ps', 'rental_id'));
    }

    public function store(Request $request)
    {

        SetRental_M::create([
            'name' => $request->name,
            'tv_id' => $request->tv_id,
            'ps_id' => $request->ps_id,
            'harga_per_jam' => $request->harga_per_jam,
            'rental_id' => $request->rental_id,
        ]);

        return redirect()->route('setrental.index', ['rental_id' => $request->rental_id]);
    }

    public function show($id)
    {
        $setRental = SetRental_M::with(['tv', 'ps'])->findOrFail($id);
        return view('setrental.show', compact('setRental'));
    }

    public function edit($id)
    {
        $setRental = SetRental_M::findOrFail($id);
        $tvs = TV_M::all();
        $ps = PS_M::all();
        return view('setrental.edit', compact('setRental', 'tvs', 'ps'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'tv_id' => 'required|exists:tv_m,id',
            'ps_id' => 'required|exists:ps_m,id',
        ]);

        $setRental = SetRental_M::findOrFail($id);
        $setRental->update([
            'name' => $request->name,
            'tv_id' => $request->tv_id,
            'ps_id' => $request->ps_id,
        ]);

        return redirect()->route('setrental.index', ['rental_id' => $setRental->rental_id]);
    }

    public function destroy($id)
    {
        $setRental = SetRental_M::findOrFail($id);
        $rental_id = $setRental->rental_id;
        $setRental->delete();

        return redirect()->route('setrental.index', ['rental_id' => $rental_id]);
    }
}
