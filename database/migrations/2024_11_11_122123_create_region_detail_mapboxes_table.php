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
        Schema::create('region_detail_mapboxes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('region_detail_id')->references('id')->on('region_details');
            $table->string('name')->index();
            $table->string('latitude');
            $table->string('longitude');
            $table->text('map_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_drone')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('region_detail_mapboxes');
    }
};
