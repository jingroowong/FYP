<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;

class Admin extends Authenticatable
{
    use Notifiable, HasFactory, CanResetPassword;

    protected $guard = 'admin';
    protected $table = 'admins';
    protected $primaryKey = 'adminID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $adminID;
    protected $adminName;
    protected $password;
    protected $adminPhone;
    protected $adminEmail;
    protected $photo;

    protected $fillable = [
        'adminName', 'password', 'adminPhone', 'adminEmail', 'photo'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function getAdminID()
    {
        return $this->adminID;
    }

    public function setAdminID($adminID)
    {
        $this->adminID = $adminID;
    }

    public function getAdminName()
    {
        return $this->adminName;
    }

    public function setAdminName($adminName)
    {
        $this->adminName = $adminName;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getAdminPhone()
    {
        return $this->adminPhone;
    }

    public function setAdminPhone($adminPhone)
    {
        $this->adminPhone = $adminPhone;
    }

    public function getAdminEmail()
    {
        return $this->adminEmail;
    }

    public function setAdminEmail($adminEmail)
    {
        $this->adminEmail = $adminEmail;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

}
