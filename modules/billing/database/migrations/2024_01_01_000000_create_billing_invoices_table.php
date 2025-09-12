<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('billing_invoices', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['due', 'paid', 'overdue'])->default('due');
            $table->date('due_date');
            $table->timestamps();

            $table->index(['tenant_id', 'status'], 'billing_invoices_tenant_status_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_invoices');
    }
};
