<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Tenant;
use App\Support\AttendanceReview;
use App\Support\OnboardingService;
use App\Support\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $tenant = Tenant::factory()->create();
        app(TenantManager::class)->setTenant($tenant);
    }

    public function test_onboarding_creates_employee_with_role(): void
    {
        $role = Role::create([
            'tenant_id' => app(TenantManager::class)->tenant()->id,
            'name' => 'manager',
            'permissions' => ['employees.manage'],
        ]);

        $service = new OnboardingService;
        $employee = $service->onboard([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'role_id' => $role->id,
            'salary' => 1000,
        ]);

        $this->assertDatabaseHas('employee', [
            'id' => $employee->id,
            'tenant_id' => app(TenantManager::class)->tenant()->id,
        ]);
        $this->assertTrue($employee->hasPermission('employees.manage'));
    }

    public function test_attendance_review_calculates_hours(): void
    {
        $role = Role::create([
            'tenant_id' => app(TenantManager::class)->tenant()->id,
            'name' => 'staff',
            'permissions' => [],
        ]);

        $employee = (new OnboardingService)->onboard([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'role_id' => $role->id,
        ]);

        $employee->timesheets()->create([
            'date' => '2024-01-01',
            'clock_in' => '09:00',
            'clock_out' => '17:00',
        ]);
        $employee->timesheets()->create([
            'date' => '2024-01-02',
            'clock_in' => '10:00',
            'clock_out' => '15:00',
        ]);

        $hours = (new AttendanceReview)->totalHours($employee, Carbon::parse('2024-01-01'), Carbon::parse('2024-01-31'));

        $this->assertEquals(13, $hours);
    }
}
