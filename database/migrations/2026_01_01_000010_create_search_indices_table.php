<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_indices', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->unsignedBigInteger('entity_id');
            $table->text('content');
            $table->timestamps();

            $table->unique(['module', 'entity_id']);

            if (DB::getDriverName() !== 'sqlite') {
                $table->fullText('content');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_indices');
    }
};
