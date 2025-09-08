<?php

namespace App\Models;

use App\Support\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'disk',
        'path',
        'name',
        'mime',
        'size',
        'thumbnail_path',
    ];
}
