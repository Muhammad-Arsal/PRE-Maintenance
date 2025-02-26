<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\TenantPasswordResetNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;



class Tenant extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $table = "tenants";

    protected $fillable = [
        'name', 'email', 'password', 'status', 'property_id', 'contract_start', 'contract_end', 'deposit', 'adjust', 'left_property', 'work_phone','home_phone','note'
    ];


    public function profile(){
        return $this->hasOne(TenantProfile::class);
    }
    public function details(){
        return $this->hasMany(TenantDetails::class);
    }
    public function property(){
        return $this->hasOne(Property::class);
    }
    
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new TenantPasswordResetNotification($token));
    }

    use HasFactory;
}
