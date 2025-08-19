<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'file'
    ];

    public function reminders() {
        return $this->hasMany('App\Models\TasksReminders', 'task_id');
    }

}
