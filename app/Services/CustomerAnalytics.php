<?php

namespace App\Services;

use App\Models\Customer;

class CustomerAnalytics
{
    public function totalCustomers(): int
    {
        return Customer::count();
    }

    public function totalInteractions(): int
    {
        return Customer::withCount('interactions')->get()->sum('interactions_count');
    }
}
