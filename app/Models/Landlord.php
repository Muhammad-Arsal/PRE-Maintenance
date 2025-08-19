<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\LandlordPasswordResetNotification;

class Landlord extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Auditable;

    protected $table = "landlords"; 

    protected $fillable = [
        'name', 'email', 'password', 'status',  'title',
        'company_name',
        'work_phone',
        'home_phone',
        'commission_rate',
        'country','line1', 'line2', 'line3', 'city', 'county',
        'postcode', 'note','account_number', 'sort_code', 'account_name', 'bank', 'bank_address', 'overseas_landlord', 'tax_exemption_code',
    ];

    public function profile(){
        return $this->hasOne(LandlordProfile::class);
    }

    public function property(){
        return $this->hasMany(Property::class);
    }
    public function invoices()
    {
        return $this->hasManyThrough(Invoices::class, Property::class);
    }
    public function jobs()
    {
        return $this->hasManyThrough(Jobs::class, Property::class);
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new LandlordPasswordResetNotification($token));
    }

    use HasFactory;
}
