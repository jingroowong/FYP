<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;
    protected $primaryKey = 'transactionID';
    protected $table = 'wallet_transactions';

    public $incrementing = false; // Set this to false to prevent auto-incrementing
   
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'walletID', 'walletID');
    }

}
