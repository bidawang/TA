<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePhoneNumberIsSet
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            $cleanPhone = preg_replace('/\D/', '', $user->no_hp);
            if (empty($cleanPhone) || strlen($cleanPhone) < 12 || strlen($cleanPhone) > 14) {
                return redirect()->route('user.edit', $user->google_id)->with('warning', 'Silakan lengkapi nomor HP terlebih dahulu.');
            }
        }

        return $next($request);
    }
}

