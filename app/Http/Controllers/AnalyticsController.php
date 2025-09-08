<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        $kpi = DB::table('tenant_user_counts')
            ->where('tenant_id', $tenantId)
            ->first();

        return view('analytics.index', [
            'userCount' => $kpi->user_count ?? 0,
        ]);
    }

    public function users(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        $users = DB::table('users')
            ->where('tenant_id', $tenantId)
            ->get();

        return view('analytics.users', ['users' => $users]);
    }

    public function export(Request $request): StreamedResponse
    {
        $tenantId = $request->user()->tenant_id;
        $rows = DB::table('tenant_user_counts')
            ->where('tenant_id', $tenantId)
            ->get();

        $response = new StreamedResponse(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['tenant_id', 'user_count']);
            foreach ($rows as $row) {
                fputcsv($handle, [$row->tenant_id, $row->user_count]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="analytics.csv"');

        return $response;
    }
}
