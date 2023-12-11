<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

    use HasFactory;
    protected $primaryKey = 'walletID';
    protected $table = 'wallets';

    public $incrementing = false; // Set this to false to prevent auto-incrementing
   
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agentID');
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class, 'walletID');
    }
}
