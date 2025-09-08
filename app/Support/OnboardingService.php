<?php

namespace App\Support;

use App\Models\Employee;
use App\Models\Role;
use App\Models\User;

class OnboardingService
{
    public function onboard(array $data): Employee
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'] ?? 'password',
        ]);

        $role = Role::findOrFail($data['role_id']);
        $role->users()->attach($user->id, ['tenant_id' => $role->tenant_id]);

        return Employee::create([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'first_name' => $data['first_name'] ?? $data['name'],
            'last_name' => $data['last_name'] ?? '',
            'email' => $data['email'],
            'hire_date' => $data['hire_date'] ?? null,
            'salary' => $data['salary'] ?? 0,
        ]);
    }
}
