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
        Schema::create('region_detail_mapbox_lists', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('region_detail_mapbox_id')->references('id')->on('region_detail_mapboxes');
            $table->string('name')->index();
            $table->string('latitude');
            $table->string('longitude');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('region_detail_mapbox_lists');
    }
};
