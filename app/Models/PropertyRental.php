<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyRental extends Model
{
    use HasFactory;

    protected $table = 'property_rentals';
    protected $primaryKey = 'propertyRentalID';

    public $incrementing = false; // Set this to false to prevent auto-incrementing
    public function property()
    {
        return $this->belongsTo(Property::class, 'propertyID');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenantID', 'tenantID');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'paymentID');
    }

    public function refund()
    {
        return $this->hasOne(Refund::class, 'propertyRentalID');
    }
}