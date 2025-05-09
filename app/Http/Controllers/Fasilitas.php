<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fasilitas_M;
use App\Models\Rental_M;
use Illuminate\Support\Facades\Auth;

class Fasilitas extends Controller
{
    public function index(Request $request)
    {
        // Mengambil rental_id dari query string
        $rental_id = $request->query('rental_id');

        // Membuat query builder untuk mengambil data fasilitas dengan relasi rental
        $query = Fasilitas_M::with('rental');

        // Jika ada rental_id, filter berdasarkan rental_id
        if ($rental_id) {
            $query->where('id_rental', $rental_id);
        }

        // Ambil data fasilitas yang sudah difilter
        $fasilitas = $query->get();

        // Kirimkan data fasilitas dan rental_id ke view
        return view('fasilitas.index', compact('fasilitas', 'rental_id'));
    }

// app/Http/Controllers/FasilitasController.php

public function create(Request $request)
{
    $rental_id = $request->query('rental_id'); // Mengambil rental_id dari query string URL
// dd($rental_id);
    return view('fasilitas.create', compact('rental_id'));
}


    // Menyimpan fasilitas baru
    public function store(Request $request)
    {

        // Proses upload foto fasilitas jika ada
        if ($request->hasFile('foto_fasilitas')) {
            $foto_fasilitas = $request->file('foto_fasilitas')->store('fasilitas', 'public');
        } else {
            $foto_fasilitas = null;
        }

        // Menyimpan fasilitas baru
        Fasilitas_M::create([
            'nama_fasilitas' => $request->nama_fasilitas,
            'id_rental' => $request->id_rental,
            'google_id' => $request->google_id, // Jika ada
            'foto_fasilitas' => $foto_fasilitas
        ]);

        return redirect()->route('fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    // Menampilkan detail fasilitas
    public function show($id)
    {
        $fasilitas = Fasilitas_M::with('rental')->findOrFail($id);
        return view('fasilitas.show', compact('fasilitas'));
    }

    // Menampilkan form untuk mengedit fasilitas
    public function edit($id)
    {
        $fasilitas = Fasilitas_M::findOrFail($id);
        $rentals = Rental_M::all();
        return view('fasilitas.edit', compact('fasilitas', 'rentals'));
    }

    // Memperbarui fasilitas
    public function update(Request $request, $id)
    {
        // Validasi inputan
        $request->validate([
            'nama_fasilitas' => 'required|string|max:255',
            'id_rental' => 'required|exists:rental_m,id',
            'foto_fasilitas' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fasilitas = Fasilitas_M::findOrFail($id);

        // Proses upload foto fasilitas jika ada
        if ($request->hasFile('foto_fasilitas')) {
            // Hapus foto lama jika ada
            if ($fasilitas->foto_fasilitas) {
                \Storage::delete('public/' . $fasilitas->foto_fasilitas);
            }
            $foto_fasilitas = $request->file('foto_fasilitas')->store('fasilitas', 'public');
        } else {
            $foto_fasilitas = $fasilitas->foto_fasilitas;
        }

        // Update data fasilitas
        $fasilitas->update([
            'nama_fasilitas' => $request->nama_fasilitas,
            'id_rental' => $request->id_rental,
            'google_id' => $request->google_id, // Jika ada
            'foto_fasilitas' => $foto_fasilitas
        ]);

        return redirect()->route('fasilitas.index')->with('success', 'Fasilitas berhasil diperbarui!');
    }

    // Menghapus fasilitas
    public function destroy($id)
    {
        $fasilitas = Fasilitas_M::findOrFail($id);
        
        // Hapus foto fasilitas jika ada
        if ($fasilitas->foto_fasilitas) {
            \Storage::delete('public/' . $fasilitas->foto_fasilitas);
        }

        $fasilitas->delete();

        return redirect()->route('fasilitas.index')->with('success', 'Fasilitas berhasil dihapus!');
    }
}
