<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'email_templates';
    protected $fillable = ['type','subject','status','is_html','content'];
}
