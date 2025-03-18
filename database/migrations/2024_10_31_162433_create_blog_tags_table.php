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
        Schema::create('blog_tags', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('region_id')->references('id')->on('regions');
            $table->foreignUlid('blog_id')->references('id')->on('blogs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_tags');
    }
};
