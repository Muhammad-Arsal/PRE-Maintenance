<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $table = "properties";

    protected $fillable = [
        'tenant_id', 'landlord_id', 'type', 'management_charge', 'bedrooms', 'has_garden',
        'is_active', 'has_garage', 'has_parking', 'line1', 'line2', 'line3', 'city', 'county',
        'postcode', 'gas_certificate_due', 'eicr_due', 'epc_due', 'epc_rate'
    ];    

    public function tenant(){
        return $this->hasOne(Tenant::class);
    }
    public function landlord(){
        return $this->belongsTo(Landlord::class);
    }
}
