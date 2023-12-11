<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;

class Tenant extends Authenticatable
{
    use Notifiable, HasFactory, CanResetPassword;
    public $timestamps = false;
    protected $guard = 'tenant';
    protected $table = 'tenants';
    protected $primaryKey = 'tenantID';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $tenantID;
    protected $tenantName;
    protected $tenantEmail;
    protected $tenantPhone;
    protected $password;
    protected $photo;
    protected $tenantDOB;
    protected $gender;


    protected $fillable = [
        'tenantID','tenantName', 'tenantEmail', 'tenantPhone', 'password', 'photo', 'tenantDOB', 'gender','registerDate'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function getTenantID()
    {
        return $this->tenantID;
    }

    public function setTenantID($tenantID)
    {
        $this->tenantID = $tenantID;
    }

    public function getTenantName()
    {
        return $this->tenantName;
    }

    public function setTenantName($tenantName)
    {
        $this->tenantName = $tenantName;
    }

    public function getTenantEmail()
    {
        return $this->tenantEmail;
    }

    public function setTenantEmail($tenantEmail)
    {
        $this->tenantEmail = $tenantEmail;
    }

    public function getTenantPhone()
    {
        return $this->tenantPhone;
    }

    public function setTenantPhone($tenantPhone)
    {
        $this->tenantPhone = $tenantPhone;
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

    public function getTenantDOB()
    {
        return $this->tenantDOB;
    }

    public function setTenantDOB($tenantDOB)
    {
        $this->tenantDOB = $tenantDOB;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function appointment()
    {
        return $this->hasMany(Appointment::class, 'tenantID');
    }

    public function propertyRentals()
    {
        return $this->hasMany(PropertyRental::class, 'tenantID');
    }
}