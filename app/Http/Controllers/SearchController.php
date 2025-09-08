<?php

namespace App\Http\Controllers;

use App\Models\SearchIndex;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $module = $request->input('module');

        $query = SearchIndex::query();

        if ($module) {
            $query->where('module', $module);
        }

        if ($q) {
            $query->whereRaw('MATCH(content) AGAINST (? IN BOOLEAN MODE)', [$q.'*']);
        }

        return $query->limit(50)->get();
    }
}
