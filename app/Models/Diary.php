<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diary extends BaseModel
{
    use HasFactory;

    protected $table = "diary";

    protected $fillable = ['admin_id', 'property_id', 'entry'];

    public function admin(){
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function property(){
        return $this->belongsTo(Property::class, 'property_id');
    }
}
