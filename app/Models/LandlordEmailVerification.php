<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandlordEmailVerification extends Model
{
    use HasFactory;

    protected $table = 'landlord_email_verification';

    protected $fillable = ['token', 'landlord_id', 'email'];
}
