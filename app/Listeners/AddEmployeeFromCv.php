<?php

namespace App\Listeners;

use App\Events\CvAccepted;
use App\Events\DomainEvent;
use App\Models\Cv;
use App\Support\EventBus;
use App\Support\HrService;

class AddEmployeeFromCv
{
    public function __construct(private HrService $hr, private EventBus $bus) {}

    public function handle(Cv $cv): void
    {
        $employee = $this->hr->hireFromCv($cv);
        $cv->update(['employee_id' => $employee->id]);
        $this->bus->publish(DomainEvent::CV_ACCEPTED->value, ['cv_id' => $cv->id, 'employee_id' => $employee->id]);
        event(new CvAccepted($cv, $employee));
    }
}
