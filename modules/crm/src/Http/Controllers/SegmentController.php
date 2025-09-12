<?php

declare(strict_types=1);

namespace Modules\Crm\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Crm\Models\Segment;

class SegmentController extends Controller
{
    public function index()
    {
        $segments = Segment::all(['id', 'name']);
        if ($segments->isEmpty()) {
            return response()->json(['message' => 'segments not found'], 404);
        }

        return response()->json(['segments' => $segments]);
    }
}
