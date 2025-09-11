<?php

namespace Modules\Procurement\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Rfq extends Model
{
    use HasUlids;

    protected $table = 'procurement_rfqs';
    protected $guarded = [];
    protected $casts = [
        'items' => 'array',
    ];
}
