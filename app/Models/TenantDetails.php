<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantDetails extends Model
{
    public $timestamps = false;
    protected $table = 'tenant_details';
    protected $fillable = ['id', 'name', 'phone_number', 'work_phone', 'home_phone', 'email', 'tenant_id'];
    use HasFactory;
}
