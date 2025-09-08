<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fileables', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('file_id');
            $table->unsignedBigInteger('fileable_id');
            $table->string('fileable_type');
            $table->timestamps();

            $table->primary(['tenant_id', 'file_id', 'fileable_id', 'fileable_type']);
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fileables');
    }
};
