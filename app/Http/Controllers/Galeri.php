<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Galeri_M;
use App\Models\Rental_M;

class Galeri extends Controller
{
    public function index(Request $request)
    {
        $rental_id = $request->query('rental_id'); // Mengambil rental_id dari query string URL

        $query = Galeri_M::with('rental'); // Mengambil data galeri dengan relasi rental

        if ($rental_id) {
            $query->where('id_rental', $rental_id); // Menyaring galeri berdasarkan id_rental
        }

        $galeris = $query->get();
        return view('galeri.index', compact('galeris', 'rental_id'));
    }

    // Menampilkan form untuk menambah galeri baru
    public function create(Request $request)
    {
        $rental_id = $request->query('rental_id'); // Mengambil rental_id dari URL

        return view('galeri.create', compact('rental_id'));
    }

    // Menyimpan galeri baru
    public function store(Request $request)
{
    $fotoPath = $request->hasFile('foto_fasilitas')
        ? $request->file('foto_fasilitas')->store('galeri_foto', 'public')
        : null;

    Galeri_M::create([
        'nama_foto' => $request->nama_foto,
        'deskripsi' => $request->deskripsi,
        'carousel' => $request->carousel,
        'id_rental' => $request->rental_id,
        'google_id' => $request->google_id,
        'foto_fasilitas' => $fotoPath,
    ]);

    return redirect()->route('galeri.index', ['rental_id' => $request->rental_id]);
}


    // Menampilkan detail galeri
    public function show($id)
    {
        $galeri = Galeri_M::with('rental')->findOrFail($id);

        return view('galeri.show', compact('galeri'));
    }

    // Menampilkan form untuk mengedit galeri
    public function edit($id)
    {
        $galeri = Galeri_M::findOrFail($id);

        return view('galeri.edit', compact('galeri'));
    }

    // Mengupdate data galeri
    public function update(Request $request, $id)
{
    $request->validate([
        'nama_foto' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'carousel' => 'required|boolean',
    ]);

    $galeri = Galeri_M::findOrFail($id);

    if ($request->hasFile('foto_fasilitas')) {
        $fotoPath = $request->file('foto_fasilitas')->store('galeri_foto', 'public');
        $galeri->foto_fasilitas = $fotoPath;
    }

    $galeri->nama_foto = $request->nama_foto;
    $galeri->deskripsi = $request->deskripsi;
    $galeri->carousel = $request->carousel;
    $galeri->google_id = $request->google_id;
    $galeri->save();

    return redirect()->route('galeri.index', ['rental_id' => $galeri->id_rental]);
}


    // Menghapus galeri
    public function destroy($id)
    {
        $galeri = Galeri_M::findOrFail($id);
        $galeri->delete();

        return redirect()->route('galeri.index', ['rental_id' => $galeri->id_rental]);
    }
}
