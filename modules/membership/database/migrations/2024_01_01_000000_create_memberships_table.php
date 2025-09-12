<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('customer_id');
            $table->enum('tier', ['silver', 'gold']);
            $table->date('expiry');
            $table->enum('status', ['active', 'expired'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'customer_id']);
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
