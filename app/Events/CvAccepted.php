<?php

namespace App\Events;

use App\Models\Cv;
use App\Models\Employee;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CvAccepted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Cv $cv, public Employee $employee) {}
}
