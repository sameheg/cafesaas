<?php

namespace App\Events;

use App\Models\InventoryItem;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventoryStockLow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public InventoryItem $item) {}
}
