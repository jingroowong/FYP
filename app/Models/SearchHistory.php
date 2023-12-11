<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $primaryKey = 'searchID';
    public $incrementing = false;
    protected $table = 'search_histories';

    protected $fillable = [
        'searchID',
        'searchDate',
        'clickTime',
        'tenantID',
        'propertyID',
    ];

}
