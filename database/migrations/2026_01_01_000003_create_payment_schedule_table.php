<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_schedule', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('lease_id');
            $table->date('due_date');
            $table->unsignedInteger('amount_cents');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('lease_id')->references('id')->on('lease');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_schedule');
    }
};
