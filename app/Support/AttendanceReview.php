<?php

namespace App\Support;

use App\Models\Employee;
use App\Models\Timesheet;
use Illuminate\Support\Carbon;

class AttendanceReview
{
    public function totalHours(Employee $employee, Carbon $start, Carbon $end): float
    {
        return Timesheet::where('employee_id', $employee->id)
            ->whereBetween('date', [$start, $end])
            ->get()
            ->sum(fn ($sheet) => $sheet->hours());
    }
}
