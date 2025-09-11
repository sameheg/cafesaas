<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('procurement_pos', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->ulid('bid_id');
            $table->foreign('bid_id')->references('id')->on('procurement_bids');
            $table->string('supplier_id');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['draft', 'sent', 'received', 'matched', 'cancelled', 'varied'])->default('draft');
            $table->timestamps();
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurement_pos');
    }
};
