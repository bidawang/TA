<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Rating extends Controller
{
    public function store(Request $request, $rentalId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        Rating::updateOrCreate(
            ['user_id' => $user->id, 'rental_id' => $rentalId],
            ['rating' => $request->rating, 'komentar' => $request->komentar]
        );

        return redirect()->back()->with('success', 'Rating berhasil dikirim.');
    }
}
