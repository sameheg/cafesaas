<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'report_date',
        'total_sales_cents',
        'cash_in_drawer_cents',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];
}
