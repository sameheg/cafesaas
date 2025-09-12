<?php

namespace Modules\Franchise\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TemplateUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public string $templateId, public array $changes)
    {
    }

    public function broadcastAs(): string
    {
        return 'franchise.template.updated@v1';
    }
}
