<?php

namespace App\Models;

use App\Support\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use BelongsToTenant, HasFactory;

    protected $table = 'employee';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'role_id',
        'first_name',
        'last_name',
        'email',
        'hire_date',
        'salary',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function hasPermission(string $permission): bool
    {
        return $this->user?->hasPermission($permission) ?? false;
    }
}
