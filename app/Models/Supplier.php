<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';

    protected $fillable = ['tenant_id', 'branch_id', 'name', 'contact'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
