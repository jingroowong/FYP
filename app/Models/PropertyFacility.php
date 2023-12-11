<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyFacility extends Model
{
    use HasFactory;
    protected $table = 'property_facilities';

    protected $fillable = [
        'propertyID', 'facilityID',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'propertyID');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facilityID');
    }

}
