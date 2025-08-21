<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminEmailVerification extends Model
{
    use HasFactory;

    protected $table = 'admin_email_verification';

    protected $fillable = ['token', 'admin_id', 'email'];
}
