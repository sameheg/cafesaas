<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('franchise_templates', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('type');
            $table->json('data');
            $table->unsignedInteger('version')->default(1);
            $table->string('status')->default('Local');
            $table->timestamps();
            $table->index(['tenant_id', 'type'], 'tenant_id_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('franchise_templates');
    }
};
