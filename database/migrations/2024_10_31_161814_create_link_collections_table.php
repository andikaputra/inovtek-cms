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
        Schema::create('link_collections', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('region_id')->references('id')->on('regions');
            $table->text('url');
            $table->string('display');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_social_media')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_collections');
    }
};
