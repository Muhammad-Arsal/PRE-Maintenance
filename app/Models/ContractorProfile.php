<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorProfile extends BaseModel
{
    use HasFactory;

    protected $table = 'contractor_profile';

    protected $fillable = ['contractor_id', 'profile_image', 'phone_number'];
}
