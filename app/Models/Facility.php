<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;
    protected $table = 'facilities'; // Adjust if your table name is different
    protected $primaryKey = 'facilityID';

    public $incrementing = false; // Set this to false to prevent auto-incrementing

    protected $fillable = [
        'facilityID', 'facilityName', 'facilityIcon',
        // Add other attributes if needed
    ];

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_facilities', 'facilityID', 'propertyID');
    }
}
