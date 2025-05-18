<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Transaksi_M;
use App\Models\Rental_M;
use App\Models\UserWallet_M;
use App\Models\WalletLogs_M;
use App\Models\Wallet_M;

class View extends Controller
{
    private $tripayApiKey;
    private $tripayApiUrl;

    public function __construct()
    {
        $this->tripayApiKey = env('TRIPAY_API_KEY');
        $this->tripayApiUrl = 'https://tripay.co.id/api-sandbox';
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'developer') {
            $transaksi = Transaksi_M::with('pembayaran','setRental')->latest()->get();
        } elseif ($user->role === 'admin') {
            $rental = Rental_M::where('google_id', $user->google_id)->first();

            $transaksi = Transaksi_M::with('pembayaran','setRental')
                ->where('id_rental', $rental->id_rental)
                ->latest()
                ->get();
        } else {
            abort(403, 'Unauthorized role');
        }

        $transaksiTripay = [];

        foreach ($transaksi as $tx) {
            if (!empty($tx->pembayaran?->reference)) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->tripayApiKey,
                    'Accept' => 'application/json'
                ])->get($this->tripayApiUrl . '/transaction/detail', [
                    'reference' => $tx->pembayaran->reference
                ]);

                if ($response->successful() && isset($response['data'])) {
                    $transaksiTripay[$tx->id_transaksi] = $response['data'];
                }
            }
        }

        return view('riwayat.index', compact('transaksi', 'transaksiTripay'));
    }

    public function show($id)
    {
        $transaksi = Transaksi_M::with('pembayaran')->findOrFail($id);
        $tripayData = null;

        if (!empty($transaksi->pembayaran?->reference)) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->tripayApiKey,
                'Accept' => 'application/json'
            ])->get($this->tripayApiUrl . '/transaction/detail', [
                'reference' => $transaksi->pembayaran->reference
            ]);

            if ($response->successful() && isset($response['data'])) {
                $tripayData = $response['data'];
            }
        }

        return view('riwayat.show', compact('transaksi', 'tripayData'));
    }

    public function pendapatan($id, Request $request)
{
    $rental = Rental_M::with('alamat')->findOrFail($id);
    $googleId = $rental->google_id ?? null;

    $query = Transaksi_M::where('id_rental', $id);

    // Filter tanggal
    if ($request->has('start') && $request->has('end')) {
        $start = $request->start;
        $end = $request->end;
        $query->whereBetween('created_at', [$start, $end]);
        $filterLabel = "Custom: $start s.d. $end";
    } else {
        $query->whereDate('created_at', now()->toDateString());
        $filterLabel = "Hari Ini (" . now()->format('d M Y') . ")";
    }

    $totalPendapatan = $query->sum('total');

    // Paginate dengan mempertahankan query string
    $transaksis = $query->latest()->paginate(5)->withQueryString();

    // Ambil saldo dompet user
    $userWallet = null;
    if ($googleId) {
        $userWallet = UserWallet_M::where('google_id', $googleId)
                                  ->where('id_rental', $id)
                                  ->first();
    }

    // Ambil log dompet
    $walletLogs = WalletLogs_M::where('id_rental', $id)
                               ->orderByDesc('created_at')
                               ->get();

    // Metode penarikan
    $withdrawMethods = Wallet_M::where('id_rental', $id)
                                ->where('google_id', $googleId)
                                ->get();

    return view('pendapatan.index', compact(
        'rental',
        'totalPendapatan',
        'transaksis',
        'filterLabel',
        'userWallet',
        'walletLogs',
        'withdrawMethods'
    ));
}



}
