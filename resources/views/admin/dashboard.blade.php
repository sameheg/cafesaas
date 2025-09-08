<h1>Super Admin Dashboard</h1>

<h2>Tenants</h2>
<ul>
@foreach($tenants as $tenant)
    <li>{{ $tenant->name }}: {{ implode(', ', $tenant->modules->pluck('module')->toArray()) }}</li>
@endforeach
</ul>

<h2>Module Usage</h2>
<ul>
@foreach($moduleUsage as $module => $count)
    <li>{{ $module }}: {{ $count }}</li>
@endforeach
</ul>

<h2>Recent Audit Logs</h2>
<ul>
@foreach($recentLogs as $log)
    <li>{{ $log->action }} - {{ $log->meta['module'] ?? '' }} - {{ $log->created_at }}</li>
@endforeach
</ul>
