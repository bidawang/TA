<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran_M;
use Carbon\Carbon;

class TripayCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $signatureKey = env('TRIPAY_PRIVATE_KEY');
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
        $json = $request->getContent();

        if (hash_hmac('sha256', $json, $signatureKey) !== $callbackSignature) {
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
        }

        $data = $request->input('data');
        $reference = $data['reference'];
        $status = $data['status'];

        $pembayaran = Pembayaran_M::where('reference', $reference)->first();
        if (!$pembayaran) {
            return response()->json(['success' => false, 'message' => 'Pembayaran tidak ditemukan'], 404);
        }

        $pembayaran->update([
            'status' => $status,
            'paid_at' => $status === 'PAID' ? Carbon::parse($data['paid_at']) : null,
        ]);

        return response()->json(['success' => true]);
    }
}
