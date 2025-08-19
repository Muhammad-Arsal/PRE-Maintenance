<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ContractorPasswordResetNotification;

class Contractor extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Auditable;

    protected $table = "contractors"; 

    protected $fillable = [
        'name', 'email', 'password', 'status', 'title', 'country','line1', 'line2', 'line3', 'city', 'county',
        'postcode', 'note','company_name','work_phone', 'fax', 'contact_type', 'contractor_type_id',
    ];

    public function profile(){
        return $this->hasOne(ContractorProfile::class);
    }

    public function contractorType()
    {
        return $this->belongsTo(ContractorType::class, 'contractor_type_id');
    }    

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ContractorPasswordResetNotification($token));
    }

    use HasFactory;
}
