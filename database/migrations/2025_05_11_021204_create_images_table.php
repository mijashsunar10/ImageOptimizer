<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('original_name');  // Original filename
            $table->string('title')->nullable();  // Optional title
            $table->string('path');  // Storage path
            $table->string('mime_type');  // Image type (e.g., image/jpeg)
            $table->integer('size');  // File size in bytes
            $table->integer('width');  // Image width in pixels
            $table->integer('height');  // Image height in pixels
            $table->boolean('optimization_failed')->default(false);  // Flag for failed optimizations
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
