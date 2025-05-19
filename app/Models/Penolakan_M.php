<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penolakan_M extends Model
{
    use HasFactory;
    protected $table = 'penolakan'; // Sesuaikan dengan nama tabel Anda

    // Kolom-kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'id_wallet_logs',
        'keterangan',
    ];

    public function walletLog() {
        return $this->belongsTo(WalletLogs_M::class);
    }
}
