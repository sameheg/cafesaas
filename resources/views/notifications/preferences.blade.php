<h1>Notification Preferences</h1>
<table>
    <thead>
        <tr>
            <th>Template</th>
            <th>Channel</th>
            <th>Enabled</th>
        </tr>
    </thead>
    <tbody>
        @foreach($preferences as $pref)
            <tr>
                <td>{{ $pref->template_key }}</td>
                <td>{{ $pref->channel }}</td>
                <td>{{ $pref->enabled ? 'Yes' : 'No' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
