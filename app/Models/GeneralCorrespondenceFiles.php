<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralCorrespondenceFiles extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'landlord_id',
        'tenant_id',
        'parent_id',
        'property_id',
        'contractor_id',
        'type',
        'copy_to_correspondence',
        'file_name',
        'file_description',
        'original_name',
        'text',
        'admin_id',
        'link',
        'original_link',
        'is_text',
        'is_email',
    ];

    protected $table = "general_correspondence_files";
}
