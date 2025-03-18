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
        Schema::create('region_detail_infos', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('region_id')->references('id')->on('regions');
            $table->text('intro_video_url');
            $table->text('tutorial_video_url');
            $table->text('mitigation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('region_detail_infos');
    }
};
