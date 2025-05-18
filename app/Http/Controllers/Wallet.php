<?php

namespace App\Http\Controllers;

use App\Models\Wallet_M;
use App\Models\UserWallet_M;
use App\Models\WalletLogs_M;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Wallet extends Controller
{
    public function index()
{
    $wallet = WalletLogs_M::where('type', 'out')->get();

    return view('wallet.index', compact('wallet'));
}


            public function create()
            {
                $providers = [
                    'GOPAY' => 'GoPay',
                    'OVO' => 'OVO',
                    'DANA' => 'DANA',
                    'SHOPEEPAY' => 'ShopeePay',
                    'LINKAJA' => 'LinkAja',
                ];
            
                return view('wallet.create', compact('providers'));
            }
            
        
            public function store(Request $request)
            {
                $request->validate([
                    'provider' => 'required|string',
                    'kode_provider' => 'required|string',
                ]);
            
                $googleId = auth()->user()->google_id; // atau sesuai auth guard kamu
                $idRental = session('id_rental'); // pastikan sudah disimpan sebelumnya
            
                Wallet_M::create([
                    'provider'      => $request->provider,
                    'kode_provider' => $request->kode_provider,
                    'google_id'     => $googleId,
                    'id_rental'     => $idRental,
                ]);
            
                return redirect()->route('user.profile', $googleId)
                ->with('success', 'Metode pembayaran berhasil ditambahkan.');
                }
            
                public function edit($id)
                {
                    $user = Auth::user();
                
                    $wallet = Wallet_M::where('id_wallet', $id)
                        ->where('google_id', $user->google_id)
                        ->firstOrFail();
                
                    $providers = [
                        'GOPAY'     => 'GoPay',
                        'OVO'       => 'OVO',
                        'DANA'      => 'DANA',
                        'SHOPEEPAY' => 'ShopeePay',
                        'LINKAJA'   => 'LinkAja',
                    ];
                
                    return view('wallet.edit', compact('wallet', 'providers'));
                }
                
                public function update(Request $request, $id)
                {
                    $user = Auth::user();
                
                    $wallet = Wallet_M::where('id_wallet', $id)
                        ->where('google_id', $user->google_id)
                        ->firstOrFail();
                
                    $validated = $request->validate([
                        'provider'      => 'required|string|max:100',
                        'kode_provider' => 'required|string|max:100',
                    ]);
                
                    $wallet->update($validated);
                
                    return redirect()->route('user.profile', $user->google_id)
                        ->with('success', 'Metode pembayaran berhasil diperbarui.');
                }
                
                public function destroy($id)
                {
                    $user = Auth::user();
                
                    $wallet = Wallet_M::where('id_wallet', $id)
                        ->where('google_id', $user->google_id)
                        ->firstOrFail();
                
                    $wallet->delete();
                
                    return redirect()->route('user.profile', $user->google_id)
                        ->with('success', 'Metode pembayaran berhasil dihapus.');
                }

                public function tariktunai(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string',
        ]);

        // Ambil dompet user
        $wallet = UserWallet_M::where('google_id', $user->google_id)->first();

        if (!$wallet) {
            return back()->withErrors(['error' => 'Dompet tidak ditemukan.']);
        }

        if ($request->amount > $wallet->balance) {
            return back()->withErrors(['amount' => 'Jumlah penarikan melebihi saldo dompet.']);
        }

        // Simpan pengajuan penarikan (buat model WalletWithdraw dan tabelnya)
        WalletLogs_M::create([
            'amount'        => $request->amount,
            'method'        => $request->method,
            'google_id'     => $user->google_id,
            'id_rental'     => session('id_rental'),
            'type'        => 'out',
            'status'        => 'pending',
        ]);

        // Kurangi saldo wallet (optional, bisa sesuai logic aplikasi)
        $wallet->balance -= $request->amount;
        $wallet->save();

        return redirect()->route('user.profile', $user->google_id)
            ->with('success', 'Pengajuan penarikan saldo berhasil dikirim.');
    }
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,disetujui,ditolak',
    ]);

    $walletLog = WalletLogs_M::findOrFail($id);
    $walletLog->status = $request->status;
    $walletLog->save();

    return redirect()->back()->with('success', 'Status berhasil diperbarui.');
}

        }
        