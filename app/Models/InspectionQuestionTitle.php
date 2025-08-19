<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionQuestionTitle extends BaseModel
{
    use HasFactory;

    protected $table = 'inspection_questions_title';

    protected $fillable = [
        'title',
    ];

    public function inspectionQuestionSections()
    {
        return $this->hasMany(InspectionQuestionSection::class);
    }
}
