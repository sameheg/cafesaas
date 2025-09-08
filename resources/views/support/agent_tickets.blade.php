<h1>Tickets assigned to {{ $user->name }}</h1>
<ul>
@foreach($tickets as $ticket)
    <li>{{ $ticket->subject }} - {{ $ticket->priority }} - {{ $ticket->status }}</li>
@endforeach
</ul>
