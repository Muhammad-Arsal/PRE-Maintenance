<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantProfile extends BaseModel
{
    use HasFactory;

    protected $table = 'tenant_profile';

    protected $fillable = ['tenant_id', 'profile_image', 'phone_number'];
}
