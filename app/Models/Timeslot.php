<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model
{
    use HasFactory;
    protected $primaryKey = 'timeslotID';

    protected $fillable = [
        'timeslotID',
        'startTime',
        'endTime',
        'date',
        'agentID',
    ];
    public $incrementing = false; // Set this to false to prevent auto-incrementing

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agentID', 'agentID');
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'timeslotID', 'timeslotID');
    }

    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'timeslotID', 'timeslotID');
    }

}
