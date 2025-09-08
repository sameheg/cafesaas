<?php

namespace App\Support;

use App\Models\Cv;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Support\Str;

class HrService
{
    public function hireFromCv(Cv $cv): Employee
    {
        $nameParts = Str::of($cv->name)->explode(' ');
        $firstName = $nameParts->first();
        $lastName = $nameParts->slice(1)->implode(' ');

        $roleId = Role::where('tenant_id', $cv->tenant_id)
            ->where('name', 'user')
            ->value('id');

        return Employee::create([
            'tenant_id' => $cv->tenant_id,
            'role_id' => $roleId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $cv->email,
            'hire_date' => now(),
        ]);
    }
}
