<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran_M;
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

        $pembayaran = Pembayaran_M::where('reference', $reference)->first();
        if (!$pembayaran) {
            return response()->json(['success' => false, 'message' => 'Pembayaran tidak ditemukan'], 404);
        }

        $paidAt = null;
        if ($status === 'PAID' && !empty($data['paid_at'])) {
            try {
                // paid_at dalam format timestamp UNIX
                $paidAt = Carbon::createFromTimestamp($data['paid_at']);
            } catch (\Exception $e) {
                $paidAt = null;
            }
        }

        $pembayaran->update([
            'status' => $status,
            'paid_at' => $paidAt,
        ]);

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        \Log::error('Error in Tripay callback: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Internal server error'], 500);
    }
}

}
