<?php

namespace App\Models;

use App\Support\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = ['tenant_id', 'vars_json'];

    protected $casts = [
        'vars_json' => 'array',
    ];

    public function toCss(): string
    {
        $vars = collect($this->vars_json ?? [])->map(
            fn ($value, $key) => "--{$key}: {$value};"
        )->implode(' ');

        return ":root { {$vars} }";
    }
}
