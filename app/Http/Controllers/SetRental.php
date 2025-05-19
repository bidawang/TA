<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use App\Models\SetRental_M;
use App\Models\TV_M;
use App\Models\PS_M;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    $query = SetRental_M::with(['tv', 'ps', 'rental', 'transaksi']);

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
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'tv_id' => 'required',
        'ps_id' => 'required',
        'harga_per_jam' => 'required|numeric|min:0',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $fotoPath = null;
    if ($request->hasFile('foto')) {
        $fotoPath = $request->file('foto')->store('setrental_foto', 'public');
    }

    try {
        SetRental_M::create([
            'name' => $validated['name'],
            'tv_id' => $validated['tv_id'],
            'ps_id' => $validated['ps_id'],
            'harga_per_jam' => $validated['harga_per_jam'],
            'rental_id' => session('id_rental'),
            'foto' => $fotoPath,
        ]);
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])->withInput();
    }

    return redirect()->route('setrental.index', ['rental_id' => session('id_rental')])
                     ->with('success', 'Data berhasil disimpan!');
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tv_id' => 'required|exists:tv_m,id',
            'ps_id' => 'required|exists:ps_m,id',
            'harga_per_jam' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $setRental = SetRental_M::findOrFail($id);
    
        // Hapus foto lama jika upload baru
        if ($request->hasFile('foto')) {
            if ($setRental->foto && Storage::disk('public')->exists($setRental->foto)) {
                Storage::disk('public')->delete($setRental->foto);
            }
            $validated['foto'] = $request->file('foto')->store('setrental_foto', 'public');
        }
    
        $setRental->update($validated);
    
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
