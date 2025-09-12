<?php

namespace Modules\Franchise\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Franchise\Models\FranchiseBranch;
use Modules\Franchise\Models\FranchiseTemplate;

class ReportController extends Controller
{
    public function aggregate()
    {
        $data = [
            'branches' => FranchiseBranch::count(),
            'templates' => FranchiseTemplate::count(),
        ];

        if (empty($data['branches']) && empty($data['templates'])) {
            return response()->json(['data' => null], 404);
        }

        return response()->json(['data' => $data]);
    }
}
