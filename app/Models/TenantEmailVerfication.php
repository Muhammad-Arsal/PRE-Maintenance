<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantEmailVerfication extends BaseModel
{
    use HasFactory;

    protected $table = 'tenant_email_verification';

    protected $fillable = ['token', 'tenant_id', 'email'];
}
