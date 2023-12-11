<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Chatify\Traits\UUID;

class ChMessage extends Model
{
    use UUID;
    protected $table = 'ch_messages';
    protected $primaryKey = 'id';
    public $incrementing = false; // Set this to false to prevent auto-incrementing

    protected $fillable = [
        'id',
        'form_id',
        'to_id',
    ];

}
