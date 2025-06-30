<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionQuestion extends Model
{
    use HasFactory;
    
    protected $table = 'inspection_questions';

    public $timestamps = false;

    protected $fillable = [
        'inspection_question_section_id',
        'question',
    ];

    public function inspectionQuestionSection()
    {
        return $this->belongsTo(InspectionQuestionSection::class);
    }
}
