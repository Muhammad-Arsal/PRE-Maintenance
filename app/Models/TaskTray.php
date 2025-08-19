<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskTray extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'task_tray';

    protected $fillable = [
        'name'
    ];
}
