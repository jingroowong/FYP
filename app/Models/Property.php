<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $primaryKey = 'propertyID';
    public $incrementing = false; // Set this to false to prevent auto-incrementing

    protected $fillable = [
        'propertyID',
        'propertyName', // Add 'propertyName' to the $fillable array
        'propertyDesc',
        'propertyType',
        'propertyAddress',
        'propertyAvailability',
        'bedroomNum',
        'bathroomNum',
        'buildYear',
        'squareFeet',
        'furnishingType',
        'rentalAmount',
        'depositAmount',
        'stateID',
        'agentID',
        'roomType',
        'housingType',
        'created_at',
    ];

    public function propertyPhotos()
    {
        return $this->hasMany(PropertyPhoto::class, 'propertyID');
    }

    public function propertyFacilities()
    {
        return $this->hasMany(PropertyFacility::class, 'propertyID');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agentID');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'propertyID', 'propertyID');
    }

    public function propertyRental()
    {
        return $this->hasOne(PropertyRental::class, 'propertyID');
    }

public function facilities()
{
    return $this->belongsToMany(Facility::class, 'property_facilities', 'propertyID', 'facilityID');
}
public function photos()
{
    return $this->hasMany(PropertyPhoto::class, 'propertyID');
}

public function state()
{
    return $this->belongsTo(State::class, 'stateID');
}

public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewItemID', 'propertyID');
    }
}
