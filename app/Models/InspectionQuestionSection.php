<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionQuestionSection extends BaseModel
{
    use HasFactory;

    protected $table = 'inspection_questions_section';

    public $timestamps = false;

    protected $fillable = [
        'inspection_question_title_id',
        'section_name',
    ];

    public function inspectionQuestion()
    {
        return $this->hasMany(InspectionQuestion::class);
    }

    public function inspectionQuestionTitle()
    {
        return $this->belongsTo(InspectionQuestionTitle::class);
    }
}
