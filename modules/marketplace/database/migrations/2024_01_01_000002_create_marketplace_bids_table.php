<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_bids', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('rfq_id');
            $table->ulid('store_id');
            $table->decimal('price', 10, 2)->check('price > 0');
            $table->enum('status', ['open','awarded']);
            $table->timestamps();
            $table->index(['rfq_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_bids');
    }
};
