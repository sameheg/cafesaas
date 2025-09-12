<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('franchise_branches', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('name');
            $table->json('overrides')->nullable();
            $table->timestamps();
            $table->index(['tenant_id', 'name'], 'tenant_id_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('franchise_branches');
    }
};
