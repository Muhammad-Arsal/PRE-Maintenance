<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDocs extends Model
{
    use HasFactory;

    public $table = 'event_docs';

    protected $fillable = [
        'event_id', 'file_name'
    ];
}
