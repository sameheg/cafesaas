<!DOCTYPE html>
<html>
<head>
    <title>Analytics Users</title>
</head>
<body>
    <h1>Users</h1>
    <ul>
        @foreach ($users as $user)
            <li>{{ $user->email }}</li>
        @endforeach
    </ul>
    <p><a href="{{ route('analytics.index') }}">Back</a></p>
</body>
</html>
