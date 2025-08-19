<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\TenantPasswordResetNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;



class Tenant extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Auditable;

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
