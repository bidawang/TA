<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi_M;
use App\Models\SetRental_M;
use App\Models\Rental_M;
use App\Models\Tripay_M;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Transaksi extends Controller
{
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

        if ($jenis === 'bukan') {
            $jamMulai = Carbon::now($timezone);
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
            'keterangan'    => $request->input('keterangan'),
        ]);

        // ==== PROSES TRIPAY ====
        $paymentMethod = $request->input('payment_method');
        $merchantRef = 'TRX-' . Str::random(10);
$amount = $totalHarga;
$kodeMerchant = env('TRIPAY_MERCHANT_CODE'); // Misalnya: "T35752"
$privateKey = env('TRIPAY_PRIVATE_KEY');

$signature = hash_hmac('sha256', $kodeMerchant . $merchantRef . $amount, $privateKey);


        $tripayResponse = Http::withHeaders([
    'Authorization' => 'Bearer ' . env('TRIPAY_API_KEY'),
])->post('https://tripay.co.id/api-sandbox/transaction/create', [
    'method'        => $paymentMethod,
    'merchant_ref'  => $merchantRef,
    'amount'        => $amount,
    'signature'     => $signature,
    'customer_name' => auth()->user()->name ?? 'Guest',
    'customer_email'=> auth()->user()->email ?? 'guest@example.com',
    'customer_phone'=> auth()->user()->phone ?? '08123456789',  // Menambahkan nomor telepon
    'order_items'   => [
        [
            'sku'      => 'rental-ps',
            'name'     => 'Rental PS - ' . $jumlahJam . ' Jam',
            'price'    => $amount,
            'quantity' => 1,
        ]
    ],
    'callback_url'  => route('tripay.callback'),
    'return_url'    => route('setrental.index', ['rental_id' => $idRental]),
    'expired_time'  => now()->addHours(1)->timestamp,
]);


        if ($tripayResponse->successful()) {
            $data = $tripayResponse->json()['data'];
            // dd($data);
            Tripay_M::create([
                'transaksi_id'   => $transaksi->id_transaksi,
                'merchant_ref'   => $data['merchant_ref'],
                'reference'      => $data['reference'],
                'amount'         => $data['amount'],
                'payment_method' => $data['payment_method'],
                'status'         => $data['status'],
                'checkout_url'   => $data['checkout_url'],
                'paid_at'        => null,
            ]);

            return redirect()->away($data['checkout_url']);
        } else {
            $transaksi->delete();

            $errorMessage = $tripayResponse->json()['message'] ?? 'Gagal membuat transaksi pembayaran. Silakan coba lagi nanti.';
            return back()->withErrors(['tripay' => $errorMessage])->withInput();
        }
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
}