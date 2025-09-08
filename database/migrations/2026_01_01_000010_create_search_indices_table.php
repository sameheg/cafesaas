<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->fullText('content');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_indices');
    }
};
