<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandlordProfile extends BaseModel
{
    use HasFactory;

    protected $table = 'landlord_profile';

    protected $fillable = ['landlord_id', 'profile_image', 'phone_number'];
}
