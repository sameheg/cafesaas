<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;

class TicketController extends Controller
{
    public function customer(Customer $customer)
    {
        $tickets = Ticket::where('customer_id', $customer->id)->get();

        return view('support.customer_tickets', [
            'customer' => $customer,
            'tickets' => $tickets,
        ]);
    }

    public function agent(User $user)
    {
        $tickets = Ticket::where('agent_id', $user->id)->get();

        return view('support.agent_tickets', [
            'user' => $user,
            'tickets' => $tickets,
        ]);
    }
}
