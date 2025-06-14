<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

use App\Models\SetRental_M;
use App\Models\TV_M;
use App\Models\PS_M;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SetRental extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Pengecualian: method yang bebas diakses
            $exceptMethods = ['getTripayChannels', 'index'];

            // Cek nama method saat ini
            $currentMethod = $request->route()->getActionMethod();

            if (!in_array($currentMethod, $exceptMethods)) {
                if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'developer'])) {
                    return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
                }
            }

            return $next($request);
        });
    }
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
        // Wajib login
        if (!auth()->check()) {
            return redirect()->route('dashboard')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil rental_id dari query string
        $rental_id = $request->query('rental_id');

        // Jika tidak ada rental_id, redirect ke dashboard
        if (!$rental_id) {
            return redirect()->route('dashboard')->with('error', 'Akses tanpa rental_id tidak diperbolehkan.');
        }

        // Buat query builder dengan eager loading relasi
        $query = SetRental_M::with(['tv', 'ps', 'rental', 'transaksi', 'storages']);

        // Filter berdasarkan rental_id
        $query->where('rental_id', $rental_id);

        // Ambil data setRental
        $setRentals = $query->get();

        // Ambil data games secara manual (karena beda koneksi DB)
        foreach ($setRentals as $setRental) {
            $games = collect();
            foreach ($setRental->storages as $storage) {
                $game = \App\Models\Game_M::find($storage->id_game);
                if ($game) {
                    $games->push($game);
                }
            }
            $setRental->games = $games; // Tambahkan properti games secara manual
        }

        // Ambil channel Tripay
        $channels = $this->getTripayChannels();

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

        // Ambil tv_id dan ps_id yang sudah dipakai di setrental untuk rental_id ini
        $usedTvIds = SetRental_M::where('rental_id', $rental_id)
            ->pluck('tv_id')
            ->filter()
            ->unique()
            ->toArray();

        $usedPsIds = SetRental_M::where('rental_id', $rental_id)
            ->pluck('ps_id')
            ->filter()
            ->unique()
            ->toArray();

        // Ambil TV yang belum dipakai
        $tvs = TV_M::whereNotIn('id', $usedTvIds)->get();

        // Ambil PS yang belum dipakai
        $ps = PS_M::whereNotIn('id', $usedPsIds)->get();

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
        $setRental = SetRental_M::with(['tv', 'ps', 'games'])->findOrFail($id);
        return view('setrental.show', compact('setRental'));
    }


    public function edit($id)
    {
        $setRental = SetRental_M::findOrFail($id);

        $rental_id = session('id_rental');

        // Ambil tv_id dan ps_id yang sudah dipakai di setrental untuk rental_id ini
        $usedTvIds = SetRental_M::where('rental_id', $rental_id)
            ->pluck('tv_id')
            ->filter()
            ->unique()
            ->toArray();

        $usedPsIds = SetRental_M::where('rental_id', $rental_id)
            ->pluck('ps_id')
            ->filter()
            ->unique()
            ->toArray();

        // Ambil TV yang belum dipakai
        $tvs = TV_M::where('id', $usedTvIds)->get();

        // Ambil PS yang belum dipakai
        $ps = PS_M::where('id', $usedPsIds)->get();

        return view('setrental.edit', compact('setRental', 'tvs', 'ps'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
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

    public function selesai($id)
    {
        // dd($id);
        $setRental = SetRental_M::findOrFail($id);
        $setRental->status = 'tidak';
        $setRental->save();

        return redirect()->back()->with('success', 'Rental berhasil diakhiri.');
    }

    public function toMaintenance($id)
    {
        $set = SetRental_M::findOrFail($id);
        $set->status = 'maintenance';
        $set->save();

        return back()->with('success', 'Set berhasil diubah ke status maintenance.');
    }

    public function toAktif($id)
    {
        $set = SetRental_M::findOrFail($id);
        $set->status = 'tidak';
        $set->save();

        return back()->with('success', 'Set kembali tersedia.');
    }




    public function destroy($id)
    {
        $setRental = SetRental_M::findOrFail($id);
        $rental_id = $setRental->rental_id;
        $setRental->delete();

        return redirect()->route('setrental.index', ['rental_id' => $rental_id]);
    }
}
