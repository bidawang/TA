<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi_M;
use App\Models\MetodePembayaran_M;
use App\Models\SetRental_M;
use App\Models\Rental_M;
use App\Models\Tripay_M;
use App\Models\User;
use App\Models\UserWallet_M;
use App\Models\WalletLogs_M;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Transaksi extends Controller
{
    public function index()
{
    $authUser = Auth::user();
    $googleId = $authUser->google_id;
    $role = $authUser->role;

    // Default pagination limit
    $perPage = 3;

    // Data transaksi & user (profil)
    $trans = null;
    $user = null;

   if ($role === 'user') {
    $user = User::where('google_id', $googleId)->first();
    $filter = request('filter'); // ambil parameter filter dari URL
    $query = Transaksi_M::where('google_id', $googleId)
        ->with('rental', 'user', 'setRental');

    if ($filter === 'pending') {
        $query->whereIn('status', ['pending', 'disetujui']);
    } elseif ($filter === 'selesai') {
        $query->whereIn('status', ['ditolak', 'selesai']);
    }

    $trans = $query->latest()->paginate($perPage);}
    elseif ($role === 'admin' || $role === 'developer') {
    $filter = request('filter');
    $query = Transaksi_M::with('rental');

    if ($role === 'admin') {
        $user = Rental_M::where('google_id', $googleId)->first();
        if (!$user) {
            abort(404, 'Rental tidak ditemukan untuk admin/developer ini.');
        }
        $query->where('id_rental', $user->id);
    }

    // Tambahkan filter status untuk admin & developer
    if ($filter && $filter !== 'semua') {
        $query->where('status', $filter);
    }

    $trans = $query->latest()->paginate($perPage);
}


    elseif ($role === 'admin') {
$user = Rental_M::where('google_id', $googleId)->first();

        // Jika rental tidak ditemukan, kembalikan error
        if (!$user) {
            abort(404, 'Rental tidak ditemukan untuk admin/developer ini.');
        }

        $trans = Transaksi_M::where('id_rental', $user->id)
                    ->with('rental')
                    ->latest()
                    ->paginate($perPage);
    }
    elseif ($role === 'developer') {
        $trans = Transaksi_M::with('rental')
                    ->latest()
                    ->paginate($perPage);
    }

    else {
        abort(403, 'Role tidak dikenali.');
    }

    return view('Transaksi.index', compact('trans', 'user'));
}

public function store(Request $request)
{
    $jenis = $request->input('status');
    $jumlahJam = (int) $request->input('jumlah_jam');
    $idRental = $request->input('id_rental');

    $rental = Rental_M::with('alamat')->findOrFail($idRental);
    $provinsiString = $rental->alamat->provinsi ?? null;
    $provinsiName = $provinsiString ? explode('|', $provinsiString)[1] : null;
    $timezone = $this->getTimezoneFromProvince(strtolower($provinsiName));
    $jamMulai = Carbon::now($timezone);

    if ($request->filled('device_time')) {
        try {
            $jamMulai = Carbon::parse($request->input('device_time'));
        } catch (\Exception $e) {
            return back()->withErrors(['device_time' => 'Format waktu perangkat tidak valid.'])->withInput();
        }
    }

    if ($jenis === 'booking') {
        if ($request->input('start_option') === 'selesai') {
            $lastTransaksi = Transaksi_M::where('id_rental', $idRental)
                ->where('id_set_rental', $request->input('id_set_rental'))
                ->orderByDesc('created_at')
                ->first();
            $jamMulai = $lastTransaksi && $lastTransaksi->jam_selesai
                ? Carbon::parse($lastTransaksi->jam_selesai)
                : Carbon::now($timezone);
        } else {
            $manualHour = (int) $request->input('manual_hour', 0);
            $manualMinute = (int) $request->input('manual_minute', 0);
            $manualTimeStr = sprintf('%s %02d:%02d:00', $jamMulai->format('Y-m-d'), $manualHour, $manualMinute);
            $manualTime = Carbon::createFromFormat('Y-m-d H:i:s', $manualTimeStr, $timezone);
            if ($manualTime->lt(Carbon::now($timezone))) {
                return back()->withErrors(['manual_time' => 'Waktu manual tidak boleh lebih awal dari sekarang.'])->withInput();
            }
            $jamMulai = $manualTime;
        }
    }

    $jamSelesai = $jamMulai->copy()->addHours($jumlahJam);
    $totalHarga = (int) str_replace(['Rp', '.', ' '], '', $request->input('total_harga'));

    $transaksi = Transaksi_M::create([
    'id_set_rental' => $request->input('id_set_rental'),
    'id_rental'     => $idRental,
    'jam_mulai'     => $jamMulai,
    'jam_selesai'   => $jamSelesai,
    'jumlah_jam'    => $jumlahJam,
    'total'         => $totalHarga,
    'jenis'         => $jenis,
    'status'        => 'pending',
    'keterangan'    => $request->input('keterangan'),
    'google_id'     => Auth::user()->google_id
]);

MetodePembayaran_M::create([
    'id_transaksi'   => $transaksi->id_transaksi,
    'payment_method' => $request->input('payment_method'),
]);

    SetRental_M::where('id', $request->input('id_set_rental'))
        ->update(['status' => 'dipakai']);

    // Booking ditunda proses pembayarannya
    if ($jenis === 'booking') {
        return redirect()->route('setrental.index', ['rental_id' => $idRental])
                         ->with('success', 'Booking berhasil! Menunggu persetujuan sebelum pembayaran.');
    }

    // Kalau bukan booking, tetap langsung selesai tanpa Tripay
    return redirect()->route('setrental.index', ['rental_id' => $idRental])
                     ->with('success', 'Transaksi berhasil tanpa pembayaran online.');
}

    private function getTimezoneFromProvince(string $province)
    {
        $wib = ['aceh', 'sumatera utara', 'sumatera barat', 'riau', 'kepulauan riau', 'jambi', 'bengkulu', 'sumatera selatan', 'bangka belitung', 'lampung', 'jakarta', 'jawa barat', 'banten', 'jawa tengah', 'yogyakarta', 'jawa timur'];
        $wita = ['bali', 'ntb', 'nusa tenggara barat', 'ntt', 'nusa tenggara timur', 'kalimantan barat', 'kalimantan tengah', 'kalimantan selatan', 'kalimantan timur', 'kalimantan utara', 'sulawesi selatan', 'sulawesi tengah', 'sulawesi tenggara', 'sulawesi barat', 'gorontalo'];
        $wit = ['maluku', 'maluku utara', 'papua', 'papua barat'];

        if (in_array($province, $wib)) return 'Asia/Jakarta';
        if (in_array($province, $wita)) return 'Asia/Makassar';
        if (in_array($province, $wit)) return 'Asia/Jayapura';

        return config('app.timezone', 'Asia/Jakarta');
    }

    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:batal,disetujui,ditolak',
    ]);

    $transaksi = Transaksi_M::findOrFail($id);

    $user = auth()->user();

    // Logic akses
    if ($user->role === 'user' && $request->status === 'batal') {
        $transaksi->status = 'batal';
    } elseif (in_array($user->role, ['admin', 'developer']) && in_array($request->status, ['disetujui', 'ditolak'])) {
        if ($transaksi->status === 'pending') {
            $transaksi->status = $request->status;
        } else {
            return back()->with('error', 'Status tidak bisa diubah.');
        }
    } else {
        return back()->with('error', 'Akses ditolak.');
    }

    $transaksi->save();

    return back()->with('success', 'Status transaksi berhasil diperbarui.');
}

public function bayar($id)
{
    $transaksi = Transaksi_M::with(['rental'])->findOrFail($id);
    if ($transaksi->status !== 'disetujui') {
        return back()->withErrors(['msg' => 'Transaksi belum disetujui.']);
    }

    $rental = $transaksi->rental;
    $amount = $transaksi->total;
    $merchantRef = 'TRX-' . Str::random(10);
    $kodeMerchant = env('TRIPAY_MERCHANT_CODE');
    $privateKey = env('TRIPAY_PRIVATE_KEY');
    $signature = hash_hmac('sha256', $kodeMerchant . $merchantRef . $amount, $privateKey);

    $tripayResponse = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('TRIPAY_API_KEY'),
    ])->post('https://tripay.co.id/api-sandbox/transaction/create', [
        'method'         => $transaksi['metodePembayaran']->payment_method,
        'merchant_ref'   => $merchantRef,
        'amount'         => $amount,
        'signature'      => $signature,
        'customer_name'  => auth()->user()->name ?? 'heker',
        'customer_email' => auth()->user()->email ?? 'email@heker.com',
        'customer_phone' => auth()->user()->phone ?? '08123456789',
        'order_items'    => [
            [
                'sku'      => 'rental-ps',
                'name'     => 'Rental PS - ' . $transaksi->jumlah_jam . ' Jam',
                'price'    => $amount,
                'quantity' => 1,
            ]
        ],
        'callback_url'   => route('tripay.callback'),
        'return_url'     => route('setrental.index', ['rental_id' => $transaksi->id_rental]),
        'expired_time'   => now()->addHours(1)->timestamp,
    ]);

    if ($tripayResponse->successful()) {
        $data = $tripayResponse->json()['data'];

        Tripay_M::create([
            'transaksi_id' => $transaksi->id_transaksi,
            'merchant_ref' => $data['merchant_ref'],
            'reference'    => $data['reference'],
            'checkout_url' => $data['checkout_url'],
        ]);

        return redirect()->away($data['checkout_url']);
    } else {
$errorMessage = $tripayResponse->json()['message'] ?? 'Tidak diketahui, coba ulangi.';
return back()->withErrors([
    'tripay' => 'Gagal membuat transaksi pembayaran: ' . $errorMessage
]);
    }
}

}