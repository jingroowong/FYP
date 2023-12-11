<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'reviewID';
    public $incrementing = false;
    protected $table = 'reviews';
    protected $dates = ['reviewDate'];
    
    protected $fillable = [
        'reviewID',
        'comment',
        'rating',
        'reviewItemID',
        'reviewerID',
        'ParentReviewID',
    ];


    public function agent()
    {
        return $this->belongsTo(Agent::class, 'reviewerID', 'agentID');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'reviewerID', 'tenantID');
    }
    public function property()
    {
        return $this->belongsTo(Property::class, 'reviewItemID', 'propertyID');
    }
}
