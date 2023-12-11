<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;

class Agent extends Authenticatable
{
    use Notifiable, HasFactory, CanResetPassword;
    public $timestamps = false;
    protected $guard = 'agent';
    protected $table = 'agents';
    protected $primaryKey = 'agentID';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $agentID;
    protected $agentName;
    protected $agentPhone;
    protected $agentEmail;
    protected $password;
    protected $photo;
    protected $licenseNum;

    protected $fillable = [
        'agentID',
        'agentName',
        'agentPhone',
        'agentEmail',
        'password',
        'status',
        'photo',
        'licenseNum',
        'registerDate'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function getAgentID()
    {
        return $this->agentID;
    }

    public function setAgentID($agentID)
    {
        $this->agentID = $agentID;
    }

    public function getAgentName()
    {
        return $this->agentName;
    }

    public function setAgentName($agentName)
    {
        $this->agentName = $agentName;
    }

    public function getAgentPhone()
    {
        return $this->agentPhone;
    }

    public function setAgentPhone($agentPhone)
    {
        $this->agentPhone = $agentPhone;
    }

    public function getAgentEmail()
    {
        return $this->agentEmail;
    }

    public function setAgentEmail($agentEmail)
    {
        $this->agentEmail = $agentEmail;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    public function getLicenseNum()
    {
        return $this->licenseNum;
    }

    public function setLicenseNum($licenseNum)
    {
        $this->licenseNum = $licenseNum;
    }

    // Define a relationship with Wallet model
    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'agentID');
    }

    // Define a relationship with Property model
    public function properties()
    {
        return $this->hasMany(Property::class, 'agentID');
    }

    public function timeslots()
    {
        return $this->hasMany(Timeslot::class, 'agentID');
    }

    public function propertyRentals()
    {
        return $this->hasManyThrough(
            PropertyRental::class,
            Property::class,
            'agentID',
            'propertyID'
        );
    }
}
