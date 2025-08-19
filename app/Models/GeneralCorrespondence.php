<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralCorrespondence extends BaseModel
{
    use HasFactory;

    protected $table = 'general_correspondence';

    protected $fillable = [
        'parent_id', 'name', 'admin_id', 'link', 'landlord_id', 'type', 'copy_to_correspondence', 'tenant_id', 'property_id', 'contractor_id'
    ];
}
