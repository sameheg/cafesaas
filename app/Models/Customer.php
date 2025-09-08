<?php

namespace App\Models;

use App\Notifications\CustomerCreated;
use App\Support\BelongsToTenant;
use App\Support\HasFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use BelongsToTenant, HasFactory, HasFiles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'tenant_id',
    ];

    protected static function booted(): void
    {
        static::created(function (Customer $customer) {
            if ($customer->email) {
                $customer->notify(new CustomerCreated);
            }
        });
    }

    public function notes()
    {
        return $this->hasMany(CustomerNote::class);
    }

    public function interactions()
    {
        return $this->hasMany(CustomerInteraction::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function recordInteraction(string $type, ?string $details = null): CustomerInteraction
    {
        return $this->interactions()->create([
            'type' => $type,
            'details' => $details,
            'interaction_at' => now(),
        ]);
    }
}
