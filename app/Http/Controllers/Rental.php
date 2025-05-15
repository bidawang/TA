<?php

namespace App\Http\Controllers;

use App\Models\Rental_M;
use App\Models\Alamat_M;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Rental extends Controller
{
    public function dashboard(Request $request)
{
     $query = Rental_M::with(['alamat', 'ratings']);

    if (!empty($request->search)) {
    $query->where('nama', 'like', '%' . $request->search . '%');
}


    $rentals = $query->get();

    $topRentals = Rental_M::withAvg('ratings', 'rating')
        ->orderByDesc('ratings_avg_rating')
        ->take(5)
        ->get();
// dd($request);
    return view('welcome', compact('rentals', 'topRentals'));
}


    public function index()
    {
$rentals = Rental_M::with(['alamat', 'ratings'])->get();

    // Hitung dan tambahkan properti rating ke setiap rental
    foreach ($rentals as $rental) {
        $rental->ratings_avg_rating = $rental->averageRating() ?? 0;
    }

    // Ambil 3 rental dengan rating tertinggi
    $topRentals = $rentals->sortByDesc('ratings_avg_rating')->take(3);
        return view('rental.index', compact('rentals'));
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

        Rental_M::create([
            'nama' => $validatedData['nama'],
            'nik' => $validatedData['nik'],
            'deskripsi' => $validatedData['deskripsi'],
            'id_alamat' => $alamat->id_alamat,
            'logo' => $logoPath,
        ]);

        return redirect()->route('rental.index')->with('success', 'Rental berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $rental = Rental_M::with('alamat')->findOrFail($id);

        
        return view('rental.edit', compact('rental'));
    }

    public function update(Request $request, $id)
{
    // Validasi data dasar (yang wajib selalu ada)
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

    // Ambil data rental
    $rental = Rental_M::findOrFail($id);

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
