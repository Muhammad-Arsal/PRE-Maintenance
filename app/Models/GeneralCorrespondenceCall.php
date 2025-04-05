<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralCorrespondenceCall extends Model
{
    use HasFactory;

    protected $table = 'general_correspondence_call';

    protected $fillable = ['is_task','task_id','type','landlord_id', 'admin_id', 'description','date', 'call_type', 'is_call', 'parent_id', 'time', 'time_to', 'copy_to_correspondence'];
}
