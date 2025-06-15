<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating_M;

class Rating extends Controller
{
    public function store(Request $request)
    {


        Rating_M::create([
            'user_id' => auth()->user()->google_id,
            'rental_id' => $request->rental_id,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return back()->with('success', 'Ulasan berhasil ditambahkan.');
    }


    public function updateByUser(Request $request, $id)
    {
        $rating = Rating_M::findOrFail($id);

        if (auth()->user()->google_id !== $rating->user_id) {
            abort(403);
        }

        $request->validate([
            'komentar' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $rating->update([
            'komentar' => $request->komentar,
            'rating' => $request->rating,
        ]);

        return back()->with('success', 'Komentar berhasil diperbarui.');
    }
}
