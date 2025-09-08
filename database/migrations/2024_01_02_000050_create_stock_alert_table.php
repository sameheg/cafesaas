<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_alert', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('inventory_item_id');
            $table->integer('quantity');
            $table->integer('threshold');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_alert');
    }
};
