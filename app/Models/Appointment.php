<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $primaryKey = 'appID';
    protected $fillable = [
        'status', 'name', 'email', 'contactNo', 'headcount', 'message', 'propertyID', 'tenantID', 'timeslotID'
    ];

    public $incrementing = false; // Set this to false to prevent auto-incrementing

// Relationships
    public function property()
    {
        return $this->belongsTo(Property::class, 'propertyID', 'propertyID');
    }

public function tenant()
{
    return $this->belongsTo(Tenant::class, 'tenantID', 'tenantID');
}

public function timeslot()
{
    return $this->belongsTo(Timeslot::class, 'timeslotID', 'timeslotID');
}

}
