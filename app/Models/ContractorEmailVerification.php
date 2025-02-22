<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorEmailVerification extends Model
{
    use HasFactory;

    protected $table = 'contractor_email_verification';

    protected $fillable = ['token', 'contractor_id', 'email'];
}
