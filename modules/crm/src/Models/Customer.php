<?php

declare(strict_types=1);

namespace Modules\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Customer extends Model
{
    use HasUlids;

    protected $table = 'crm_customers';

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'rfm_score',
        'state',
    ];

    protected $casts = [
        'rfm_score' => 'int',
        'state' => CustomerState::class,
    ];

    public function noActivity(): void
    {
        $this->state = CustomerState::Lapsed;
        $this->save();
    }

    public function campaignRedeem(): void
    {
        $this->state = CustomerState::Reactivated;
        $this->save();
    }

    public function longInactivity(): void
    {
        $this->state = CustomerState::Churned;
        $this->save();
    }
}
