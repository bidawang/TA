<?php

namespace App\Http\Controllers;

use App\Models\Rental_M;
use App\Models\Alamat_M;
use App\Models\Wallet_M;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Rental extends Controller
{
    public function dashboard(Request $request)
{
    $query = Rental_M::with(['alamat', 'ratings'])
        ->where('status', 'aktif');

    if (!empty($request->search)) {
        $query->where('nama', 'like', '%' . $request->search . '%');
    }

    // Tambahkan withAvg agar ratings_avg_rating tersedia
    $rentals = $query->withAvg('ratings', 'rating')->get();

    $topRentals = Rental_M::withAvg('ratings', 'rating')
        ->orderByDesc('ratings_avg_rating')
        ->take(5)
        ->get();

    return view('welcome', compact('rentals', 'topRentals'));
}



public function index()
{
    // Ambil data rental aktif dan off dengan relasi alamat dan rating
    $rentalsAktif = Rental_M::with(['alamat', 'ratings'])
        ->where('status', 'aktif')
        ->get();

    $rentalsOff = Rental_M::with(['alamat', 'ratings'])
        ->where('status', 'off')
        ->get();

    // Tambahkan properti rata-rata rating ke setiap rental aktif
    foreach ($rentalsAktif as $rental) {
        $rental->ratings_avg_rating = $rental->averageRating() ?? 0;
    }

    // Tambahkan properti rating juga ke rental off (opsional)
    foreach ($rentalsOff as $rental) {
        $rental->ratings_avg_rating = $rental->averageRating() ?? 0;
    }

    // Ambil 3 rental aktif dengan rating tertinggi
    $topRentals = $rentalsAktif->sortByDesc('ratings_avg_rating')->take(3);

    // Kirim data ke view
    return view('rental.index', compact('rentalsAktif', 'rentalsOff', 'topRentals'));
}


    public function create()
    {
        return view('rental.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'alamat_lengkap' => 'required|string|max:255',
            'provinsi' => 'required|string',
            'kota' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'kode_pos' => 'required|string|max:10',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $alamat = new Alamat_M([
            'alamat_lengkap' => $validatedData['alamat_lengkap'],
            'provinsi' => $validatedData['provinsi'],
            'kota' => $validatedData['kota'],
            'kecamatan' => $validatedData['kecamatan'],
            'kelurahan' => $validatedData['kelurahan'],
            'rt' => $validatedData['rt'],
            'rw' => $validatedData['rw'],
            'kode_pos' => $validatedData['kode_pos'],
        ]);
        $alamat->save();

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $rental = Rental_M::create([
            'nama' => $validatedData['nama'],
            'nik' => $validatedData['nik'],
            'no_hp' => $validatedData['no_hp'],
            'deskripsi' => $validatedData['deskripsi'],
            'id_alamat' => $alamat->id_alamat,
            'logo' => $logoPath,
            'google_id' => Auth::user()->google_id,

        ]);
        
        if ($request->has('provider') && $request->has('kode_provider')) {
            foreach ($request->provider as $index => $provider) {
                $kodeProvider = $request->kode_provider[$index] ?? null;
                if ($provider && $kodeProvider) {
                    Wallet_M::create([
                        'provider' => $provider,
                        'kode_provider' => $kodeProvider,
                        'id_rental' => $rental->id,
                        'google_id' => Auth::user()->google_id,
                    ]);
                }
            }
        }
if (auth()->user()->role === 'admin') {
    return redirect()->route('rental.index')->with('success', 'Tunggu kelanjutannya nya di whatsapp anda');
} else {
    return redirect()->route('dashboard')->with('success', 'Tunggu kelanjutannya nya di whatsapp anda');
}
    }

    public function edit($id)
    {
        $rental = Rental_M::with('alamat')->findOrFail($id);

        
        return view('rental.edit', compact('rental'));
    }

    public function update(Request $request, $id)
{
    $rental = Rental_M::findOrFail($id);

    // Jika hanya ingin update status
    if ($request->has('status')) {
    $request->validate([
        'status' => 'required|in:aktif,off',
    ]);

    $isAktifBaru = $request->status === 'aktif' && $rental->status !== 'aktif';

    $rental->update(['status' => $request->status]);

    // Cek jika pertama kali diaktifkan
    if ($isAktifBaru) {
        // Ubah role user menjadi admin berdasarkan google_id dari rental
        \App\Models\User::where('google_id', $rental->google_id)
            ->update(['role' => 'admin']);
    }

    // Jika request dari AJAX, return JSON
    if ($request->ajax()) {
        return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui']);
    }

    return redirect()->back()->with('success', 'Status rental berhasil diperbarui!');
}


    // Validasi data lengkap
    $validatedData = $request->validate([
        'nama' => 'required|string|max:255',
        'nik' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'logo' => 'nullable|file|image|max:2048',
    ]);

    // Jika edit_alamat dicentang, validasi juga data alamat
    if ($request->has('edit_alamat')) {
        $request->validate([
            'alamat_lengkap' => 'required|string|max:255',
            'provinsi' => 'required|string',
            'kota' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'rt' => 'required|string|max:5',
            'rw' => 'required|string|max:5',
            'kode_pos' => 'required|string|max:10',
        ]);
    }

    // Update data utama
    $rental->update([
        'nama' => $validatedData['nama'],
        'nik' => $validatedData['nik'],
        'deskripsi' => $validatedData['deskripsi'] ?? null,
    ]);

    // Update logo jika ada file baru
    if ($request->hasFile('logo')) {
        if ($rental->logo && Storage::disk('public')->exists($rental->logo)) {
            Storage::disk('public')->delete($rental->logo);
        }
        $logoPath = $request->file('logo')->store('logos', 'public');
        $rental->update(['logo' => $logoPath]);
    }

    // Jika edit_alamat dicentang, update alamat
    if ($request->has('edit_alamat')) {
        $alamat = $rental->alamat;
        $alamat->update([
            'alamat_lengkap' => $request->alamat_lengkap,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'kode_pos' => $request->kode_pos,
        ]);
    }

    return redirect()->route('rental.index')->with('success', 'Rental berhasil diperbarui!');
}



    public function showsss($id)
{
    // Ambil rental berdasarkan ID yang diberikan beserta alamat dan ratings terkait
    $rental = Rental_M::with(['alamat', 'ratings'])->findOrFail($id);

    // Hitung rata-rata rating rental
    $rental->ratings_avg_rating = $rental->averageRating() ?? 0;

    // Kembalikan data rental ke view 'rental.show'
    return view('rental.show', compact('rental'));
}


public function show($id)
{
    $rental = Rental_M::with(['alamat', 'fasilitas', 'galeri'])->findOrFail($id);
    $rental->ratings_avg_rating = $rental->ratings()->avg('rating') ?? 0;

    $carousel1 = $rental->fasilitas;
    $carousel2 = $rental->galeri;

    $user = Auth::user();

    // Ambil semua rating dengan user
    $allRatings = $rental->ratings()->with('user')->latest()->get();

    // Pisahkan rating user login (jika ada)
    $userRating = null;
    $otherRatings = $allRatings;

    if ($user) {
        $userRating = $allRatings->firstWhere('user_id', $user->google_id);
        $otherRatings = $allRatings->filter(fn($r) => $r->user_id !== $user->google_id);
    }

    // Gabungkan: userRating di atas, sisanya di bawah
    $ratings = collect();
    if ($userRating) $ratings->push($userRating);
    $ratings = $ratings->merge($otherRatings)->take(10); // ambil 10 teratas manual

    return view('rental.show', compact('rental', 'carousel1', 'carousel2', 'ratings', 'userRating'));
}


    public function destroy($id)
    {
        $rental = Rental_M::findOrFail($id);

        // Hapus file logo jika ada
        if ($rental->logo && Storage::disk('public')->exists($rental->logo)) {
            Storage::disk('public')->delete($rental->logo);
        }

        // Hapus alamat
        $rental->alamat()->delete();

        // Hapus rental
        $rental->delete();

        return redirect()->route('rental.index')->with('success', 'Rental berhasil dihapus!');
    }
}
