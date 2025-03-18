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
        Schema::create('region_details', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('slug')->index()->unique();
            $table->foreignUlid('region_id')->references('id')->on('regions');
            $table->string('village')->index();
            $table->string('latitude');
            $table->string('longitude');
            $table->text('map_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('region_details');
    }
};
