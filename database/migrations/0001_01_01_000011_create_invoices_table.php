<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('number')->unique();
            $table->string('customer_name');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_total', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('status')->default('draft');
            $table->timestamp('issued_at')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('qr_path')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
