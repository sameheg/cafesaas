<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchIndex extends Model
{
    protected $fillable = ['module', 'entity_id', 'content'];

    protected $casts = [
        'entity_id' => 'int',
    ];
}
