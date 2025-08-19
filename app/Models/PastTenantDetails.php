<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PastTenantDetails extends BaseModel
{
    use HasFactory;

    protected $table = "past_tenant_details";

    protected $fillable = [
        'property_id',
        'tenant_id',
        'contract_start',
        'contract_end',
        'left_property',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
