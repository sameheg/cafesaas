<?php

declare(strict_types=1);

namespace Modules\Crm\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasUlids;

    protected $table = 'crm_campaigns';

    protected $fillable = [
        'tenant_id',
        'segment_id',
        'status',
        'redemption_rate',
    ];

    protected $casts = [
        'status' => CampaignStatus::class,
        'redemption_rate' => 'float',
    ];

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }
}
