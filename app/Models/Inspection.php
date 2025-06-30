<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $table = 'inspections';

    protected $fillable = [
        'property_id',
        'template_id',
        'assigned_to',
        'report_type',
        'date',
        'time',
        'status',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(Admin::class, 'assigned_to', 'id');
    }

    public function template()
    {
        return $this->belongsTo(InspectionQuestionTitle::class, 'template_id', 'id');
    }

    public function responses() {
        return $this->hasMany(InspectionAnswers::class,'inspection_id');
    }
}
