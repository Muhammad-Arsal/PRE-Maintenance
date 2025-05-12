<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDetail extends Model
{
    use HasFactory;

    protected $table = 'job_detail';

    protected $fillable = [
        'jobs_id',
        'description',
        'contractor_comment',
        'admin_upload',
        'contractor_upload',
        'date',
        'price',
        'contractor_id',
        'won_contract'
    ];

    public function job()
    {
        return $this->belongsTo(Jobs::class);
    }
    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }
    
}
