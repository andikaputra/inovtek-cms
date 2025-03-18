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
        Schema::create('announcement_links', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('region_id')->references('id')->on('regions');
            $table->string('name');
            $table->text('description');
            $table->text('announcement_link')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_links');
    }
};
