<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'paymentID';

    public $incrementing = false; // Set this to false to prevent auto-incrementing

    public function propertyRental()
    {
        return $this->hasOne(PropertyRental::class, 'propertyRentalID', 'propertyRentalID');
    }
}
