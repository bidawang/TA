<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // untuk HTTP client Laravel
use App\Models\Transaksi_M;
use App\Models\Rental_M;

class View extends Controller
{
    private $tripayApiKey;
    private $tripayApiUrl;

    public function __construct()
    {
        $this->tripayApiKey = env('TRIPAY_API_KEY'); // simpan di .env
        $this->tripayApiUrl = 'https://tripay.co.id/api'; // endpoint base
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'developer') {
            $transaksi = Transaksi_M::with('pembayaran')->latest()->get();
        } elseif ($user->role === 'admin') {
            $rental = Rental_M::where('google_id', $user->google_id)->first();

            $transaksi = Transaksi_M::with('pembayaran')
                ->where('id_rental', $rental->id_rental)
                ->latest()
                ->get();
        } else {
            abort(403, 'Unauthorized role');
        }

        // Ambil data transaksi dari Tripay berdasarkan merchant_ref
        $transaksiTripay = [];

        foreach ($transaksi as $tx) {
            if (!empty($tx->pembayaran->merchant_ref)) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->tripayApiKey,
                    'Accept' => 'application/json'
                ])->get($this->tripayApiUrl . '/transaction/list', [
                    'merchant_ref' => $tx->pembayaran->merchant_ref
                ]);

                if ($response->successful() && isset($response['data'][0])) {
                    $transaksiTripay[$tx->id_transaksi] = $response['data'][0];
                }
            }
        }

        return view('riwayat.index', compact('transaksi', 'transaksiTripay'));
    }

    public function show($id)
    {
        $transaksi = Transaksi_M::with('pembayaran')->findOrFail($id);

        $tripayData = null;

        if (!empty($transaksi->pembayaran->merchant_ref)) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->tripayApiKey,
                'Accept' => 'application/json'
            ])->get($this->tripayApiUrl . '/transaction/list', [
                'merchant_ref' => $transaksi->pembayaran->merchant_ref
            ]);

            if ($response->successful() && isset($response['data'][0])) {
                $tripayData = $response['data'][0];
            }
        }

        return view('riwayat.show', compact('transaksi', 'tripayData'));
    }
}
