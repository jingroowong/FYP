<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table = 'notifications'; // Update with your actual table name if different

    protected $primaryKey = 'notificationID'; // Update with your actual primary key name if different

    public $incrementing = false; // Set this to false to prevent auto-incrementing

    protected $fillable = [
        'subject',
        'content',
        'status',
        'timestamp'
    ];
}
