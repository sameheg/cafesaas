<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lease', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('renter_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('rent_cents');
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('property');
            $table->foreign('renter_id')->references('id')->on('renter');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lease');
    }
};
