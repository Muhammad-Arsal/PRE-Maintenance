<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'model_type','model_id','action','description','url',
        'user_id','user_name','performed_at','changes'
    ];

    protected $casts = [
        'changes' => 'array',
        'performed_at' => 'datetime',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
