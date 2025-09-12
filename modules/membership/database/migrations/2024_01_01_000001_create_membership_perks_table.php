<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_perks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('tier');
            $table->string('description');
            $table->timestamps();
            $table->index('tier');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_perks');
    }
};
