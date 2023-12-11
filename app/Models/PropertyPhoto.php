<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyPhoto extends Model
{
    use HasFactory;
    protected $table = 'property_photos'; // Adjust if your table name is different

    protected $fillable = [
        'propertyID', 'propertyPath', 'dateUpload',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'propertyID');
    }
}
