<?php

namespace Modules\Procurement\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Grn extends Model
{
    use HasUlids;

    protected $table = 'procurement_grns';
    protected $guarded = [];

    public function po()
    {
        return $this->belongsTo(Po::class);
    }
}
