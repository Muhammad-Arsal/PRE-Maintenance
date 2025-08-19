<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminProfile extends BaseModel
{
    use HasFactory;

    protected $table = 'admin_profile';

    protected $fillable = ['admin_id', 'profile_image', 'phone_number'];
}
