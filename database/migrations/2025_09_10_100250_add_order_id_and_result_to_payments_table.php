<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->after('subscription_id')->constrained();
            $table->json('result')->nullable()->after('status');
            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');
            $table->dropColumn('result');
            $table->dropIndex(['tenant_id']);
        });
    }
};
