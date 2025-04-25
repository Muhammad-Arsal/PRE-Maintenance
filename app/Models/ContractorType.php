<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorType extends Model
{
    use HasFactory;
    protected $table = 'contractor_types';
    protected $fillable = ['name'];
}
