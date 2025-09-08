<h1>Tickets for {{ $customer->name }}</h1>
<ul>
@foreach($tickets as $ticket)
    <li>{{ $ticket->subject }} - {{ $ticket->priority }} - {{ $ticket->status }}</li>
@endforeach
</ul>
