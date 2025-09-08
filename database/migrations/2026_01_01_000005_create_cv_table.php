<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cv', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('job_posting_id');
            $table->string('name');
            $table->string('email');
            $table->string('resume_path')->nullable();
            $table->string('status')->default('applied');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->timestamps();
            $table->index('tenant_id');
            $table->foreign('job_posting_id')->references('id')->on('job_posting');
            $table->foreign('employee_id')->references('id')->on('employee');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv');
    }
};
