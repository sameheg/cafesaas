<h1>Billing Dashboard</h1>

<h2>Subscriptions</h2>
<ul>
    @foreach($subscriptions as $subscription)
        <li>{{ $subscription->id }} - {{ $subscription->status }} - {{ $subscription->amount_cents }} {{ $subscription->currency }}</li>
    @endforeach
</ul>

<h2>Payments</h2>
<ul>
    @foreach($payments as $payment)
        <li>{{ $payment->id }} - {{ $payment->status }} - {{ $payment->amount_cents }} {{ $payment->currency }}</li>
    @endforeach
</ul>
