<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $table = 'refunds';
    protected $primaryKey = 'refundID';

    public $incrementing = false; // Set this to false to prevent auto-incrementing
    public function propertyRental()
    {
        return $this->belongsTo(PropertyRental::class, 'propertyRentalID', 'propertyRentalID');
    }
}
