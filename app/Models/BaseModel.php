<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\Auditable;

abstract class BaseModel extends Model
{
    use Auditable;
}
