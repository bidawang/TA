<?php

namespace App\Http\Controllers;

use App\Models\SetRental_M;
use App\Models\TV_M;
use App\Models\PS_M;
use Illuminate\Http\Request;

class SetRental extends Controller
{
    public function index(Request $request)
    {
        $rental_id = $request->query('rental_id');

        $query = SetRental_M::with(['tv', 'ps']);

        if ($rental_id) {
            $query->where('rental_id', $rental_id);
        }

        $setRentals = $query->get();

        return view('setrental.index', compact('setRentals', 'rental_id'));
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
