<?php

namespace App\Models;

use App\Support\BelongsToTenant;
use App\Support\EventBus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use BelongsToTenant, HasFactory;

    protected $table = 'ticket';

    protected $fillable = [
        'customer_id',
        'agent_id',
        'category',
        'priority',
        'status',
        'subject',
        'description',
        'feedback',
        'tenant_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function resolve(?string $feedback = null): void
    {
        $this->update([
            'status' => 'resolved',
            'feedback' => $feedback,
        ]);

        app(EventBus::class)->dispatchNow('ticket.resolved', [
            'ticket_id' => $this->id,
            'customer_id' => $this->customer_id,
            'category' => $this->category,
            'priority' => $this->priority,
            'feedback' => $feedback,
        ]);
    }
}
