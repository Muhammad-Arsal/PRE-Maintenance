<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends BaseModel
{
    use HasFactory;

    protected $table = 'jobs';

    protected $fillable = [
        'status',
        'property_id',
        'other_information',
        'priority',
        'winning_contractor_id'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
    public function contractor()
    {
        return $this->belongsTo(Contractor::class, 'contractor_id');
    }
    public function jobDetail()
    {
        return $this->hasMany(JobDetail::class, 'jobs_id');
    }
    public function winningContractor()
    {
        return $this->hasOne(Contractor::class, 'id', 'winning_contractor_id');
    }
}
