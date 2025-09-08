<h1>Manage Modules for {{ $tenant->name }}</h1>
<form method="POST" action="{{ route('admin.tenants.modules.update', $tenant) }}">
    @csrf
    @foreach($modules as $key => $meta)
        <div>
            <label>
                <input type="checkbox" name="modules[]" value="{{ $key }}" {{ ($tenantModules[$key] ?? false) ? 'checked' : '' }}>
                {{ $key }}
                @if(!empty($dependencies[$key]))
                    <small>requires: {{ implode(', ', $dependencies[$key]) }}</small>
                @endif
            </label>
        </div>
    @endforeach
    <button type="submit">Save</button>
</form>
