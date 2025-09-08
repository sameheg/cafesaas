<!DOCTYPE html>
<html>
<head>
    <title>Analytics Hub</title>
</head>
<body>
    <h1>Analytics Hub</h1>
    <p>Users: {{ $userCount }}</p>
    <p><a href="{{ route('analytics.users') }}">Drill down to users</a></p>
    <p><a href="{{ route('analytics.export') }}">Export CSV</a></p>
</body>
</html>
