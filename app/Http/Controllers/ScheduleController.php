<?php

namespace App\Http\Controllers;

use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('schedule.index', [
            'schedules' => Schedule::all(['id', 'title', 'starts_at', 'ends_at']),
        ]);
    }
}
