<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('integration_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('service');
            $table->json('config_json')->nullable();
            $table->timestamps();
            $table->unique(['tenant_id', 'service']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integration_configs');
    }
};
