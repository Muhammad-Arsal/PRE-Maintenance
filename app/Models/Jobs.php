<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    protected $table = 'jobs';

    protected $fillable = [
        'status',
        'property_id',
        'contractor_id',
        'won_contract',
        'description',
        'other_information',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
    public function contractor()
    {
        return $this->belongsTo(Contractor::class, 'contractor_id');
    }

    use HasFactory;
}
