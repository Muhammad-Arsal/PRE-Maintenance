<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasksReminders extends BaseModel
{
    use HasFactory;

    protected $fillable = ['task_id', 'date', 'time', 'name', 'description'];

    public function task(){
        return $this->belongsTo('App\Models\Tasks');
    }
}
