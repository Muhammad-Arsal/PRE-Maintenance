<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionAnswers extends Model
{
    use HasFactory;

    protected $table = 'inspection_answers';

    protected $fillable = ['inspection_id','section_name','question','answer','comment'];

    public function photos() {
        return $this->hasMany(InspectionAnswersImage::class,'inspection_answer_id');
    }
}
