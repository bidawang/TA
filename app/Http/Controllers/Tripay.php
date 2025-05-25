<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran_M;
use App\Models\Tripay_M;
use App\Models\Transaksi_M;
use Carbon\Carbon;

class Tripay extends Controller
{
    public function handle(Request $request)
{
    try {
        $signatureKey = env('TRIPAY_PRIVATE_KEY');
        $callbackSignature = $request->header('X-Callback-Signature');

        if (!$callbackSignature) {
            return response()->json(['success' => false, 'message' => 'Signature header missing'], 400);
        }

        $json = $request->getContent();

        if (hash_hmac('sha256', $json, $signatureKey) !== $callbackSignature) {
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
        }

        $data = json_decode($json, true);

        $reference = $data['reference'] ?? null;
        $status = $data['status'] ?? null;

        if (!$reference || !$status) {
            return response()->json(['success' => false, 'message' => 'Data reference/status missing'], 400);
        }

        $tripay = Tripay_M::where('reference', $reference)->first();
        if (!$tripay) {
            return response()->json(['success' => false, 'message' => 'Data Tripay tidak ditemukan'], 404);
        }

        if ($status === 'PAID') {
            Transaksi_M::where('id_transaksi', $tripay->transaksi_id)
                ->update(['status' => 'selesai']);
        }

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        \Log::error('Error in Tripay callback: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Internal server error'], 500);
    }
}


}
