<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'event_type';

    protected $fillable = ['event_name', 'description', 'email_template_id'];

    public function emailTemplate() {
        return $this->belongsTo(EmailTemplate::class, 'email_template_id', 'id');
    }
}
