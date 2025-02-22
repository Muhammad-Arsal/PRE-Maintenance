<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ContractorPasswordResetNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;

class Contractor extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $table = "contractors"; 

    protected $fillable = [
        'name', 'email', 'password', 'status', 'title', 'country','line1', 'line2', 'line3', 'city', 'county',
        'postcode', 'note','company_name','work_phone', 'fax', 'contact_type'
    ];

    public function profile(){
        return $this->hasOne(ContractorProfile::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ContractorPasswordResetNotification($token));
    }

    use HasFactory;
}
