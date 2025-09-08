<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_schedule', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('cv_id');
            $table->timestamp('scheduled_at');
            $table->string('status')->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('tenant_id');
            $table->foreign('cv_id')->references('id')->on('cv');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_schedule');
    }
};
