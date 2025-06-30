<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionAnswersImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'inspection_answers_image';

    protected $fillable = ['inspection_answer_id','path'];
}
