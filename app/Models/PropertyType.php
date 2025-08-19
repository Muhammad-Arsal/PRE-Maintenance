<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyType extends BaseModel
{
    protected $table = 'property_types';
    protected $fillable = ['name'];
    use HasFactory;
}
