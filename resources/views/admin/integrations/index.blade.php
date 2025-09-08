<h1>Integration Manager</h1>

<h2>Add / Update Config</h2>
<form method="POST" action="{{ route('admin.integrations.store') }}">
    @csrf
    <input type="number" name="tenant_id" placeholder="Tenant ID">
    <input type="text" name="service" placeholder="Service">
    <textarea name="config_json" placeholder="{\"key\":\"value\"}"></textarea>
    <button type="submit">Save</button>
</form>

<h2>Configs</h2>
<ul>
@foreach($configs as $config)
    <li>Tenant {{ $config->tenant_id }} - {{ $config->service }}</li>
@endforeach
</ul>

<h2>Recent Webhook Logs</h2>
<ul>
@foreach($logs as $log)
    <li>{{ $log->service }} - {{ $log->status }} (Attempts: {{ $log->attempts }})</li>
@endforeach
</ul>
