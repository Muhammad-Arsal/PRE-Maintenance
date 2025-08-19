<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends BaseModel
{
    use HasFactory;
    protected $table = 'invoices';

    protected $fillable = [
        'property_id',
        'contractor_id',
        'job_id',
        'reference',
        'date',
        'subtotal',
        'vat_applicable',
        'vat_rate',
        'vat',
        'total',
        'line1',
        'line2',
        'line3',
        'city',
        'county',
        'postcode',
        'country',
        'description',
    ];

    public function property(){
        return $this->belongsTo(Property::class);
    }
    public function contractor(){
        return $this->belongsTo(Contractor::class);
    }
    public function job(){
        return $this->belongsTo(Jobs::class);
    }
}
