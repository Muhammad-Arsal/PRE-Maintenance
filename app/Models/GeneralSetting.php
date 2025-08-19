<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends BaseModel
{
    protected $table = 'general_settings';

    protected $fillable = ['vat_rate'];
    use HasFactory;
}
